<template>
  <div
    class="banner-energy-field"
    :class="{ 'banner-energy-field--ready': isReady }"
    aria-hidden="true"
  >
    <canvas ref="canvasElement" />
  </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, watch } from "vue";

const props = withDefaults(
  defineProps<{
    primaryColor?: string;
    glowColor?: string;
    activationSignal?: number;
  }>(),
  {
    primaryColor: "#0866FF",
    glowColor: "#35D6FF",
    activationSignal: 0,
  }
);

type RgbColor = { r: number; g: number; b: number };

type EnergyParticle = {
  x: number;
  y: number;
  previousX: number;
  previousY: number;
  velocityX: number;
  velocityY: number;
  homeX: number;
  homeY: number;
  phase: number;
  orbitRadius: number;
  orbitSpeed: number;
  size: number;
  colorIndex: number;
};

type EnergyWave = {
  x: number;
  y: number;
  startedAt: number;
  strength: number;
};

const canvasElement = ref<HTMLCanvasElement | null>(null);
const isReady = ref(false);

let context: CanvasRenderingContext2D | null = null;
let hostElement: HTMLElement | null = null;
let resizeObserver: ResizeObserver | null = null;
let intersectionObserver: IntersectionObserver | null = null;
let animationFrame = 0;
let pointerFrame = 0;
let touchReleaseTimer = 0;
let width = 1;
let height = 1;
let pixelRatio = 1;
let lastFrameTime = 0;
let isVisible = true;
let isDocumentVisible = true;
let reducedMotion = false;
let pointerActive = false;
let pointerTargetX = 0;
let pointerTargetY = 0;
let pointerCurrentX = 0;
let pointerCurrentY = 0;
let pointerEnergy = 0;
let pulseEnergy = 0;
let activationTime = 0;
let pendingPointerClientX = 0;
let pendingPointerClientY = 0;

const particles: EnergyParticle[] = [];
const energyWaves: EnergyWave[] = [];

const parseColor = (value: string, fallback: RgbColor): RgbColor => {
  const normalized = value.trim().replace("#", "");
  const expanded = normalized.length === 3
    ? normalized.split("").map((character) => `${character}${character}`).join("")
    : normalized;

  if (!/^[\da-f]{6}$/i.test(expanded)) {
    return fallback;
  }

  return {
    r: Number.parseInt(expanded.slice(0, 2), 16),
    g: Number.parseInt(expanded.slice(2, 4), 16),
    b: Number.parseInt(expanded.slice(4, 6), 16),
  };
};

const primary = parseColor(props.primaryColor, { r: 8, g: 102, b: 255 });
const glow = parseColor(props.glowColor, { r: 53, g: 214, b: 255 });
const violet = { r: 117, g: 103, b: 255 };
const palette = [primary, glow, violet];

const rgba = (color: RgbColor, alpha: number) =>
  `rgba(${color.r}, ${color.g}, ${color.b}, ${Math.max(0, Math.min(alpha, 1))})`;

const createRandom = () => {
  let seed = 4129;

  return () => {
    seed = (seed * 9301 + 49297) % 233280;
    return seed / 233280;
  };
};

const initializeParticles = () => {
  const random = createRandom();
  const isMobile = width < 768;
  const count = isMobile ? 24 : width < 1100 ? 38 : 52;
  particles.length = 0;

  for (let index = 0; index < count; index += 1) {
    const homeX = random();
    const homeY = 0.1 + random() * 0.82;
    const x = homeX * width;
    const y = homeY * height;

    particles.push({
      x,
      y,
      previousX: x,
      previousY: y,
      velocityX: (random() - 0.5) * 0.3,
      velocityY: (random() - 0.5) * 0.3,
      homeX,
      homeY,
      phase: random() * Math.PI * 2,
      orbitRadius: 30 + (index % 9) * (isMobile ? 5.2 : 7.2),
      orbitSpeed: 0.42 + random() * 0.58,
      size: (isMobile ? 0.75 : 0.9) + random() * (isMobile ? 1.05 : 1.45),
      colorIndex: index % palette.length,
    });
  }
};

const resizeCanvas = () => {
  const canvas = canvasElement.value;

  if (!canvas || !hostElement) {
    return;
  }

  width = Math.max(hostElement.clientWidth, 1);
  height = Math.max(hostElement.clientHeight, 1);
  pixelRatio = Math.min(window.devicePixelRatio || 1, 1);
  canvas.width = Math.round(width * pixelRatio);
  canvas.height = Math.round(height * pixelRatio);
  canvas.style.width = `${width}px`;
  canvas.style.height = `${height}px`;
  context = canvas.getContext("2d", { alpha: true, desynchronized: true });
  context?.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
  pointerTargetX = pointerCurrentX = width * 0.76;
  pointerTargetY = pointerCurrentY = height * 0.44;
  initializeParticles();
};

const getWaveSpeed = () => (width < 768 ? 320 : 470);

const getWaveMaximumRadius = (wave: EnergyWave) => {
  const horizontalDistance = Math.max(wave.x, width - wave.x);
  const verticalDistance = Math.max(wave.y, height - wave.y) / 0.64;
  return Math.hypot(horizontalDistance, verticalDistance) + 180;
};

const triggerEnergyWave = () => {
  if (!hostElement || reducedMotion) {
    return;
  }

  const hostBounds = hostElement.getBoundingClientRect();
  const coreBounds = hostElement
    .querySelector<HTMLElement>(".technology-core")
    ?.getBoundingClientRect();
  const x = coreBounds
    ? coreBounds.left - hostBounds.left + coreBounds.width * 0.5
    : width * 0.72;
  const y = coreBounds
    ? coreBounds.top - hostBounds.top + coreBounds.height * 0.5
    : height * 0.54;

  energyWaves.push({
    x,
    y,
    startedAt: performance.now() * 0.001,
    strength: 1,
  });

  if (energyWaves.length > 3) {
    energyWaves.shift();
  }

  startAnimation();
};

const pruneEnergyWaves = (time: number) => {
  const speed = getWaveSpeed();

  for (let index = energyWaves.length - 1; index >= 0; index -= 1) {
    const wave = energyWaves[index];

    if (!wave || (time - wave.startedAt) * speed <= getWaveMaximumRadius(wave)) {
      continue;
    }

    energyWaves.splice(index, 1);
  }
};

const drawEnergyWaves = (time: number) => {
  if (!context || !energyWaves.length) {
    return;
  }

  const speed = getWaveSpeed();
  context.save();
  context.lineCap = "round";

  energyWaves.forEach((wave) => {
    const radius = Math.max(0, (time - wave.startedAt) * speed);
    const maximumRadius = getWaveMaximumRadius(wave);
    const life = Math.max(0, 1 - radius / maximumRadius);

    for (let ringIndex = 0; ringIndex < 4; ringIndex += 1) {
      const ringRadius = radius - ringIndex * (width < 768 ? 11 : 20);

      if (ringRadius <= 0) {
        continue;
      }

      const ringStrength = (1 - ringIndex * 0.19) * life * wave.strength;
      context.strokeStyle = rgba(
        ringIndex % 2 === 0 ? glow : primary,
        ringStrength * (ringIndex === 0 ? 0.48 : 0.24)
      );
      context.lineWidth = ringIndex === 0 ? 1.8 : 0.8;
      context.beginPath();
      context.ellipse(
        wave.x,
        wave.y,
        ringRadius,
        ringRadius * 0.64,
        0,
        0,
        Math.PI * 2
      );
      context.stroke();
    }
  });

  context.restore();
};

const drawCursorField = (time: number) => {
  if (!context || pointerEnergy < 0.015) {
    return;
  }

  const energy = Math.min(1, pointerEnergy + pulseEnergy * 0.7);
  const gradient = context.createRadialGradient(
    pointerCurrentX,
    pointerCurrentY,
    0,
    pointerCurrentX,
    pointerCurrentY,
    128 + pulseEnergy * 42
  );
  gradient.addColorStop(0, rgba(glow, 0.16 * energy));
  gradient.addColorStop(0.25, rgba(primary, 0.075 * energy));
  gradient.addColorStop(1, rgba(primary, 0));
  context.fillStyle = gradient;
  context.beginPath();
  context.arc(pointerCurrentX, pointerCurrentY, 128 + pulseEnergy * 42, 0, Math.PI * 2);
  context.fill();

  context.save();
  context.translate(pointerCurrentX, pointerCurrentY);
  context.lineCap = "round";

  for (let ringIndex = 0; ringIndex < 3; ringIndex += 1) {
    const radius = 22 + ringIndex * 13 + pulseEnergy * 9;
    const rotation = time * (ringIndex % 2 === 0 ? 0.8 : -0.55) + ringIndex * 1.4;
    context.strokeStyle = rgba(
      ringIndex === 1 ? glow : primary,
      (0.2 - ringIndex * 0.035) * energy
    );
    context.lineWidth = ringIndex === 0 ? 1.25 : 0.75;
    context.beginPath();
    context.arc(0, 0, radius, rotation, rotation + Math.PI * (0.72 + ringIndex * 0.16));
    context.stroke();
  }

  context.fillStyle = rgba(glow, 0.72 * energy);
  context.shadowBlur = 14;
  context.shadowColor = rgba(glow, 0.8);
  context.beginPath();
  context.arc(0, 0, 2 + pulseEnergy * 1.5, 0, Math.PI * 2);
  context.fill();
  context.restore();
};

const drawConnections = () => {
  if (!context || pointerEnergy < 0.04) {
    return;
  }

  const maximumDistance = width < 768 ? 72 : 108;
  context.lineWidth = 0.6;

  for (let firstIndex = 0; firstIndex < particles.length; firstIndex += 1) {
    const first = particles[firstIndex];

    if (!first) {
      continue;
    }

    if (firstIndex % 5 === 0) {
      const pointerDistance = Math.hypot(
        first.x - pointerCurrentX,
        first.y - pointerCurrentY
      );

      if (pointerDistance < 190) {
        context.strokeStyle = rgba(glow, (1 - pointerDistance / 190) * 0.13 * pointerEnergy);
        context.beginPath();
        context.moveTo(first.x, first.y);
        context.lineTo(pointerCurrentX, pointerCurrentY);
        context.stroke();
      }
    }

    for (let secondIndex = firstIndex + 1; secondIndex < particles.length; secondIndex += 1) {
      if ((firstIndex + secondIndex) % 3 !== 0) {
        continue;
      }

      const second = particles[secondIndex];

      if (!second) {
        continue;
      }

      const distance = Math.hypot(first.x - second.x, first.y - second.y);

      if (distance >= maximumDistance) {
        continue;
      }

      context.strokeStyle = rgba(
        primary,
        (1 - distance / maximumDistance) * 0.12 * pointerEnergy
      );
      context.beginPath();
      context.moveTo(first.x, first.y);
      context.lineTo(second.x, second.y);
      context.stroke();
    }
  }
};

const updateAndDrawParticles = (time: number, frameFactor: number) => {
  if (!context) {
    return;
  }

  const attractionRamp = Math.min(1, Math.max(0, (time - activationTime) * 1.4));
  const waveSpeed = getWaveSpeed();
  const waveActive = energyWaves.length > 0;
  context.shadowBlur = 0;

  particles.forEach((particle, index) => {
    particle.previousX = particle.x;
    particle.previousY = particle.y;

    if (pointerActive) {
      const direction = index % 2 === 0 ? 1 : -1;
      const angle = particle.phase + time * particle.orbitSpeed * direction;
      const pulseRadius = particle.orbitRadius + Math.sin(time * 1.8 + particle.phase) * 5;
      const targetX = pointerCurrentX + Math.cos(angle) * pulseRadius;
      const targetY = pointerCurrentY + Math.sin(angle) * pulseRadius * 0.72;
      const attraction = (0.009 + (index % 4) * 0.0015) * attractionRamp;
      particle.velocityX += (targetX - particle.x) * attraction * frameFactor;
      particle.velocityY += (targetY - particle.y) * attraction * frameFactor;
      particle.velocityX += -Math.sin(angle) * 0.012 * direction * frameFactor;
      particle.velocityY += Math.cos(angle) * 0.009 * direction * frameFactor;
    } else {
      const targetX = particle.homeX * width + Math.sin(time * 0.28 + particle.phase) * 13;
      const targetY = particle.homeY * height + Math.cos(time * 0.34 + particle.phase) * 9;
      particle.velocityX += (targetX - particle.x) * 0.0014 * frameFactor;
      particle.velocityY += (targetY - particle.y) * 0.0014 * frameFactor;
    }

    energyWaves.forEach((wave) => {
      const age = time - wave.startedAt;

      if (age < 0) {
        return;
      }

      const radius = age * waveSpeed;
      const deltaX = particle.x - wave.x;
      const deltaY = particle.y - wave.y;
      const ellipticalDistance = Math.hypot(deltaX, deltaY / 0.64);
      const bandWidth = width < 768 ? 58 : 92;
      const distanceFromWave = Math.abs(ellipticalDistance - radius);

      if (distanceFromWave >= bandWidth) {
        return;
      }

      const directionLength = Math.max(Math.hypot(deltaX, deltaY), 1);
      const influence = (1 - distanceFromWave / bandWidth) * 0.44 *
        wave.strength * frameFactor;
      particle.velocityX += (deltaX / directionLength) * influence;
      particle.velocityY += (deltaY / directionLength) * influence;
    });

    const drag = Math.pow(
      pointerActive ? 0.925 : waveActive ? 0.94 : 0.955,
      frameFactor
    );
    particle.velocityX *= drag;
    particle.velocityY *= drag;

    const speed = Math.hypot(particle.velocityX, particle.velocityY);
    const maximumSpeed = pointerActive ? 11 : waveActive ? 14 : 3;

    if (speed > maximumSpeed) {
      particle.velocityX = (particle.velocityX / speed) * maximumSpeed;
      particle.velocityY = (particle.velocityY / speed) * maximumSpeed;
    }

    particle.x += particle.velocityX * frameFactor;
    particle.y += particle.velocityY * frameFactor;

    const color = palette[particle.colorIndex] || glow;
    const velocityStrength = Math.min(1, Math.hypot(particle.velocityX, particle.velocityY) / 7);
    const particleAlpha = 0.26 + pointerEnergy * 0.42 + velocityStrength * 0.24;

    if (index % 2 === 0) {
      context!.strokeStyle = rgba(color, particleAlpha * 0.28);
      context!.lineWidth = Math.max(0.4, particle.size * 0.42);
      context!.beginPath();
      context!.moveTo(particle.previousX, particle.previousY);
      context!.lineTo(
        particle.x - particle.velocityX * 2.8,
        particle.y - particle.velocityY * 2.8
      );
      context!.stroke();
    }

    if (index % 3 === 0) {
      context!.fillStyle = rgba(color, particleAlpha * 0.15);
      context!.beginPath();
      context!.arc(particle.x, particle.y, particle.size * 2.8, 0, Math.PI * 2);
      context!.fill();
    }

    context!.fillStyle = rgba(color, particleAlpha);
    context!.beginPath();
    context!.arc(
      particle.x,
      particle.y,
      particle.size * (1 + pointerEnergy * 0.22 + pulseEnergy * 0.18),
      0,
      Math.PI * 2
    );
    context!.fill();
  });

  context.shadowBlur = 0;
};

const renderFrame = (timeInMilliseconds: number) => {
  animationFrame = 0;

  if (!context || !isVisible || !isDocumentVisible) {
    return;
  }

  const time = timeInMilliseconds * 0.001;
  pruneEnergyWaves(time);
  const waveActive = energyWaves.length > 0;
  const targetFps = width < 768
    ? waveActive ? 30 : 18
    : waveActive ? 60 : 24;
  const frameInterval = 1000 / targetFps;

  if (lastFrameTime && timeInMilliseconds - lastFrameTime < frameInterval) {
    animationFrame = requestAnimationFrame(renderFrame);
    return;
  }

  const delta = Math.min((timeInMilliseconds - (lastFrameTime || timeInMilliseconds)) / 1000, 0.05);
  const frameFactor = Math.min(delta * 60, 2);
  lastFrameTime = timeInMilliseconds;

  pointerCurrentX += (pointerTargetX - pointerCurrentX) * Math.min(delta * 10, 1);
  pointerCurrentY += (pointerTargetY - pointerCurrentY) * Math.min(delta * 10, 1);
  pointerEnergy += ((pointerActive ? 1 : 0) - pointerEnergy) * Math.min(delta * 5.5, 1);
  pulseEnergy *= Math.exp(-delta * 2.35);

  context.clearRect(0, 0, width, height);
  context.globalCompositeOperation = "lighter";
  updateAndDrawParticles(time, frameFactor);
  drawEnergyWaves(time);
  drawConnections();
  drawCursorField(time);
  context.globalCompositeOperation = "source-over";

  animationFrame = requestAnimationFrame(renderFrame);
};

const startAnimation = () => {
  if (animationFrame || reducedMotion || !isVisible || !isDocumentVisible) {
    return;
  }

  lastFrameTime = performance.now();
  animationFrame = requestAnimationFrame(renderFrame);
};

const stopAnimation = () => {
  if (animationFrame) {
    cancelAnimationFrame(animationFrame);
    animationFrame = 0;
  }
};

watch(
  () => props.activationSignal,
  (signal, previousSignal) => {
    if (signal === previousSignal) {
      return;
    }

    triggerEnergyWave();
  }
);

const flushPointerPosition = () => {
  pointerFrame = 0;

  if (!hostElement) {
    return;
  }

  const bounds = hostElement.getBoundingClientRect();
  pointerTargetX = pendingPointerClientX - bounds.left;
  pointerTargetY = pendingPointerClientY - bounds.top;
};

const queuePointerPosition = (event: PointerEvent) => {
  pendingPointerClientX = event.clientX;
  pendingPointerClientY = event.clientY;

  if (!pointerFrame) {
    pointerFrame = requestAnimationFrame(flushPointerPosition);
  }
};

const handlePointerEnter = (event: PointerEvent) => {
  if (event.pointerType !== "mouse" || reducedMotion) {
    return;
  }

  queuePointerPosition(event);
  pointerActive = true;
  activationTime = performance.now() * 0.001;
  startAnimation();
};

const handlePointerMove = (event: PointerEvent) => {
  if (event.pointerType !== "mouse" || reducedMotion) {
    return;
  }

  queuePointerPosition(event);

  if (!pointerActive) {
    pointerActive = true;
    activationTime = performance.now() * 0.001;
  }
};

const handlePointerLeave = (event: PointerEvent) => {
  if (event.pointerType === "mouse") {
    pointerActive = false;
  }
};

const handlePointerDown = (event: PointerEvent) => {
  if (reducedMotion) {
    return;
  }

  queuePointerPosition(event);
  pointerActive = true;
  pulseEnergy = 1;
  activationTime = performance.now() * 0.001;

  if (event.pointerType !== "mouse") {
    window.clearTimeout(touchReleaseTimer);
    touchReleaseTimer = window.setTimeout(() => {
      pointerActive = false;
    }, 900);
  }
};

const handleVisibilityChange = () => {
  isDocumentVisible = !document.hidden;

  if (isDocumentVisible) {
    startAnimation();
  } else {
    stopAnimation();
  }
};

const removeListeners = () => {
  hostElement?.removeEventListener("pointerenter", handlePointerEnter);
  hostElement?.removeEventListener("pointermove", handlePointerMove);
  hostElement?.removeEventListener("pointerleave", handlePointerLeave);
  hostElement?.removeEventListener("pointerdown", handlePointerDown);
  document.removeEventListener("visibilitychange", handleVisibilityChange);
};

onMounted(() => {
  const canvas = canvasElement.value;
  hostElement = canvas?.closest<HTMLElement>(".technology-hero") || null;

  if (!canvas || !hostElement) {
    return;
  }

  reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  isDocumentVisible = !document.hidden;
  resizeCanvas();

  resizeObserver = new ResizeObserver(resizeCanvas);
  resizeObserver.observe(hostElement);

  intersectionObserver = new IntersectionObserver(
    ([entry]) => {
      isVisible = entry?.isIntersecting ?? true;

      if (isVisible) {
        startAnimation();
      } else {
        stopAnimation();
      }
    },
    { rootMargin: "100px 0px", threshold: 0.01 }
  );
  intersectionObserver.observe(hostElement);

  document.addEventListener("visibilitychange", handleVisibilityChange);

  isReady.value = true;
  startAnimation();
});

onBeforeUnmount(() => {
  stopAnimation();
  cancelAnimationFrame(pointerFrame);
  pointerFrame = 0;
  window.clearTimeout(touchReleaseTimer);
  resizeObserver?.disconnect();
  intersectionObserver?.disconnect();
  removeListeners();
  resizeObserver = null;
  intersectionObserver = null;
  context = null;
  hostElement = null;
  particles.length = 0;
  energyWaves.length = 0;
});
</script>

<style scoped>
.banner-energy-field {
  position: absolute;
  z-index: 0;
  inset: 0;
  overflow: hidden;
  opacity: 0;
  pointer-events: none;
  transition: opacity 600ms ease;
}

.banner-energy-field--ready {
  opacity: 0.9;
}

.banner-energy-field canvas {
  display: block;
  width: 100%;
  height: 100%;
}

@media (max-width: 767px) {
  .banner-energy-field--ready {
    opacity: 0.72;
  }
}

@media (prefers-reduced-motion: reduce) {
  .banner-energy-field {
    display: none;
  }
}
</style>
