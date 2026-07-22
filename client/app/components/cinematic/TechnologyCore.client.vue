<template>
  <div
    ref="mountElement"
    class="technology-core"
    :class="{
      'technology-core--ready': isReady,
      'technology-core--dragging': isDraggingVisual,
      'technology-core--interacted': hasInteracted,
    }"
    aria-hidden="true"
  >
    <div class="technology-core__hint">
      <span class="technology-core__hint-icon">↔</span>
      <span class="technology-core__hint-text technology-core__hint-text--desktop">
        Kéo để xoay · Nhấn tạo sóng
      </span>
      <span class="technology-core__hint-text technology-core__hint-text--mobile">
        Chạm hoặc kéo để tương tác
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from "vue";
import type {
  BufferAttribute,
  BufferGeometry,
  Group,
  LineBasicMaterial,
  Material,
  MeshBasicMaterial,
  Object3D,
  PerspectiveCamera,
  PointLight,
  PointsMaterial,
  Scene,
  ShaderMaterial,
  Texture,
  WebGLRenderer,
} from "three";

const props = withDefaults(
  defineProps<{
    primaryColor?: string;
    glowColor?: string;
  }>(),
  {
    primaryColor: "#0866FF",
    glowColor: "#35D6FF",
  }
);

const emit = defineEmits<{
  ready: [];
  unavailable: [];
  pulse: [];
}>();

const mountElement = ref<HTMLElement | null>(null);
const isReady = ref(false);
const isDraggingVisual = ref(false);
const hasInteracted = ref(false);

let scene: Scene | null = null;
let camera: PerspectiveCamera | null = null;
let renderer: WebGLRenderer | null = null;
let coreGroup: Group | null = null;
let orbitGroup: Group | null = null;
let innerCore: Object3D | null = null;
let innerCage: Object3D | null = null;
let innerSeed: Object3D | null = null;
let energyHalo: Object3D | null = null;
let pulseWaveGroup: Group | null = null;
let fresnelMaterial: ShaderMaterial | null = null;
let pulseMaterial: MeshBasicMaterial | null = null;
let neuralPointsMaterial: PointsMaterial | null = null;
let neuralLinkMaterial: LineBasicMaterial | null = null;
let energyBeamMaterial: LineBasicMaterial | null = null;
let earthLandMaterial: MeshBasicMaterial | null = null;
let earthGridMaterial: LineBasicMaterial | null = null;
let energyParticleGeometry: BufferGeometry | null = null;
let energyParticlePositionAttribute: BufferAttribute | null = null;
let energyParticleMaterial: PointsMaterial | null = null;
let energyParticlePositions: Float32Array | null = null;
let energyParticleRadii: Float32Array | null = null;
let energyParticleAngles: Float32Array | null = null;
let energyParticlePhis: Float32Array | null = null;
let energyParticleSpeeds: Float32Array | null = null;
let energyParticleBurstOffsets: Float32Array | null = null;
let energyParticleBurstVelocities: Float32Array | null = null;
let coreLight: PointLight | null = null;
let animationFrame = 0;
let resizeObserver: ResizeObserver | null = null;
let intersectionObserver: IntersectionObserver | null = null;
let isIntersecting = true;
let isDocumentVisible = true;
let isDisposed = false;
let isInitializing = false;
let reducedMotion = false;
let isMobile = false;
let isDragging = false;
let hasDragged = false;
let activePointerId: number | null = null;
let lastFrameTime = 0;
let lastPointerX = 0;
let lastPointerY = 0;
let pointerDownX = 0;
let pointerDownY = 0;
let dragRotationX = 0;
let dragRotationY = 0;
let dragVelocityX = 0;
let dragVelocityY = 0;
let pulsePhase = 1;
let baseResponsiveScale = 1;
let canvas: HTMLCanvasElement | null = null;

const ringObjects: Object3D[] = [];
const detailObjects: Object3D[] = [];

type GeoPoint = readonly [longitude: number, latitude: number];

const createDigitalEarthTexture = (compact: boolean) => {
  const canvasElement = document.createElement("canvas");
  canvasElement.width = compact ? 768 : 1024;
  canvasElement.height = compact ? 384 : 512;
  const context = canvasElement.getContext("2d");

  if (!context) {
    return canvasElement;
  }

  const continents: GeoPoint[][] = [
    [
      [-168, 69], [-151, 72], [-137, 68], [-126, 57], [-114, 50], [-101, 50],
      [-88, 47], [-81, 39], [-75, 28], [-82, 24], [-96, 19], [-106, 23],
      [-117, 32], [-125, 42], [-136, 54], [-153, 58], [-168, 69],
    ],
    [
      [-81, 12], [-72, 10], [-64, 3], [-58, -7], [-51, -15], [-48, -25],
      [-55, -37], [-64, -52], [-72, -43], [-74, -27], [-79, -9], [-81, 12],
    ],
    [
      [-52, 59], [-43, 61], [-34, 68], [-25, 77], [-39, 83], [-55, 78],
      [-63, 68], [-52, 59],
    ],
    [
      [-11, 36], [-9, 44], [-4, 49], [-7, 57], [1, 59], [9, 55],
      [18, 61], [29, 59], [40, 64], [45, 55], [38, 48], [30, 45],
      [25, 39], [16, 38], [8, 43], [0, 43], [-11, 36],
    ],
    [
      [-17, 36], [-5, 37], [10, 36], [24, 32], [34, 31], [43, 12],
      [51, 10], [48, 1], [42, -13], [33, -24], [22, -35], [13, -34],
      [5, -28], [-2, -12], [-10, 5], [-16, 17], [-17, 36],
    ],
    [
      [30, 58], [43, 65], [62, 70], [84, 77], [107, 73], [127, 61],
      [149, 59], [166, 51], [157, 44], [143, 41], [132, 34], [121, 23],
      [111, 19], [104, 8], [95, 6], [88, 21], [77, 23], [70, 31],
      [59, 29], [49, 39], [39, 47], [30, 58],
    ],
    [
      [112, -11], [125, -14], [137, -12], [151, -22], [153, -33],
      [143, -39], [130, -34], [116, -35], [112, -24], [112, -11],
    ],
    [[47, -13], [51, -17], [50, -25], [46, -26], [44, -18], [47, -13]],
    [[129, 32], [136, 35], [142, 43], [145, 49], [140, 45], [134, 38], [129, 32]],
    [[95, 5], [108, 2], [119, -4], [128, -3], [118, 3], [105, 7], [95, 5]],
  ];
  const width = canvasElement.width;
  const height = canvasElement.height;
  const project = ([longitude, latitude]: GeoPoint) => ({
    x: ((longitude + 180) / 360) * width,
    y: ((90 - latitude) / 180) * height,
  });
  const landPath = new Path2D();

  continents.forEach((continent) => {
    continent.forEach((point, index) => {
      const projected = project(point);
      if (index === 0) {
        landPath.moveTo(projected.x, projected.y);
      } else {
        landPath.lineTo(projected.x, projected.y);
      }
    });
    landPath.closePath();
  });

  context.save();
  context.shadowColor = "rgba(35, 220, 255, 0.72)";
  context.shadowBlur = compact ? 4 : 7;
  context.fillStyle = "rgba(41, 174, 224, 0.12)";
  context.fill(landPath);
  context.lineWidth = compact ? 0.7 : 0.95;
  context.strokeStyle = "rgba(128, 235, 255, 0.68)";
  context.stroke(landPath);
  context.restore();

  context.save();
  context.shadowColor = "rgba(60, 225, 255, 0.8)";
  context.shadowBlur = compact ? 3 : 5;
  context.fillStyle = "rgba(111, 238, 255, 0.78)";
  continents.forEach((continent) => {
    continent.forEach((point, index) => {
      const nextPoint = continent[(index + 1) % continent.length];
      if (!nextPoint) {
        return;
      }
      const start = project(point);
      const end = project(nextPoint);
      const segmentLength = Math.hypot(end.x - start.x, end.y - start.y);
      const sampleCount = Math.max(1, Math.round(segmentLength / (compact ? 5 : 6)));

      for (let sample = 0; sample < sampleCount; sample += 1) {
        const progress = sample / sampleCount;
        const jitter = Math.sin((index + 1) * 13.7 + sample * 4.3) * (compact ? 0.75 : 1.05);
        const x = start.x + (end.x - start.x) * progress + jitter;
        const y = start.y + (end.y - start.y) * progress - jitter * 0.45;
        context.beginPath();
        context.arc(x, y, compact ? 0.55 : 0.72, 0, Math.PI * 2);
        context.fill();
      }
    });
  });
  context.restore();

  context.save();
  context.clip(landPath);
  let textureSeed = 18437;
  const random = () => {
    textureSeed = (textureSeed * 16807) % 2147483647;
    return (textureSeed - 1) / 2147483646;
  };
  const dataNodes: Array<{ x: number; y: number; radius: number }> = [];
  const targetNodeCount = compact ? 340 : 720;

  for (let attempt = 0; attempt < targetNodeCount * 18 && dataNodes.length < targetNodeCount; attempt += 1) {
    const x = random() * width;
    const y = random() * height;

    if (context.isPointInPath(landPath, x, y)) {
      dataNodes.push({
        x,
        y,
        radius: (compact ? 0.34 : 0.42) + random() * (compact ? 0.68 : 0.92),
      });
    }
  }

  context.lineWidth = compact ? 0.28 : 0.4;
  context.strokeStyle = "rgba(71, 224, 255, 0.16)";
  dataNodes.forEach((node, index) => {
    if (index % 3 !== 0) {
      return;
    }
    const sibling = dataNodes[(index + 13 + (index % 17)) % Math.max(dataNodes.length, 1)];
    if (!sibling || Math.hypot(node.x - sibling.x, node.y - sibling.y) > width * 0.075) {
      return;
    }
    context.beginPath();
    context.moveTo(node.x, node.y);
    context.lineTo(sibling.x, sibling.y);
    context.stroke();
  });

  context.shadowColor = "rgba(111, 242, 255, 1)";
  context.shadowBlur = compact ? 2.5 : 4;
  dataNodes.forEach((node, index) => {
    context.beginPath();
    context.arc(node.x, node.y, node.radius, 0, Math.PI * 2);
    context.fillStyle = index % 31 === 0
      ? "rgba(255, 255, 255, 1)"
      : index % 7 === 0
        ? "rgba(88, 228, 255, 0.88)"
        : "rgba(54, 199, 241, 0.68)";
    context.fill();
  });
  context.restore();

  return canvasElement;
};

const isLowPowerDevice = () => {
  const device = navigator as Navigator & {
    deviceMemory?: number;
    connection?: { saveData?: boolean };
  };
  const memory = device.deviceMemory || 0;
  const cpu = navigator.hardwareConcurrency || 0;
  const isVeryLimited = Boolean((memory && memory <= 2) || (cpu && cpu <= 2));
  const isLimitedCombination = Boolean(memory && cpu && memory <= 4 && cpu <= 4);

  return Boolean(device.connection?.saveData) || isVeryLimited || isLimitedCombination;
};

const stopAnimation = () => {
  if (!animationFrame) {
    return;
  }

  cancelAnimationFrame(animationFrame);
  animationFrame = 0;
};

const renderStaticFrame = () => {
  if (renderer && scene && camera) {
    renderer.render(scene, camera);
  }
};

const getPulseAmount = () => {
  if (pulsePhase >= 1) {
    return 0;
  }

  return Math.sin(pulsePhase * Math.PI);
};

const burstEnergyParticles = () => {
  if (!energyParticleBurstVelocities || reducedMotion) {
    return;
  }

  for (let index = 0; index < energyParticleBurstVelocities.length; index += 1) {
    const currentVelocity = energyParticleBurstVelocities[index] || 0;
    const burstVelocity = 2.5 + (index % 7) * 0.16;
    energyParticleBurstVelocities[index] = Math.max(currentVelocity, burstVelocity);
  }
};

const updateEnergyParticles = (elapsed: number, delta: number, pulse: number) => {
  if (
    !energyParticlePositions ||
    !energyParticleRadii ||
    !energyParticleAngles ||
    !energyParticlePhis ||
    !energyParticleSpeeds ||
    !energyParticleBurstOffsets ||
    !energyParticleBurstVelocities ||
    !energyParticlePositionAttribute
  ) {
    return;
  }

  const particleCount = energyParticleRadii.length;
  const damping = Math.exp(-delta * 2.75);

  for (let index = 0; index < particleCount; index += 1) {
    const angle = (energyParticleAngles[index] || 0) +
      delta * (energyParticleSpeeds[index] || 0.2);
    let burstOffset = energyParticleBurstOffsets[index] || 0;
    let burstVelocity = energyParticleBurstVelocities[index] || 0;

    burstVelocity += -burstOffset * 6.2 * delta;
    burstVelocity *= damping;
    burstOffset += burstVelocity * delta;

    if (burstOffset < 0) {
      burstOffset = 0;
      burstVelocity *= -0.16;
    }

    energyParticleAngles[index] = angle;
    energyParticleBurstOffsets[index] = burstOffset;
    energyParticleBurstVelocities[index] = burstVelocity;

    const basePhi = energyParticlePhis[index] || 0;
    const phi = basePhi + Math.sin(elapsed * (0.42 + (index % 5) * 0.035) + index) * 0.075;
    const radius = (energyParticleRadii[index] || 2.5) +
      burstOffset +
      Math.sin(elapsed * 0.8 + index * 0.7) * 0.045;
    const sinPhi = Math.sin(phi);
    const positionIndex = index * 3;

    energyParticlePositions[positionIndex] = radius * sinPhi * Math.cos(angle);
    energyParticlePositions[positionIndex + 1] = radius * Math.cos(phi);
    energyParticlePositions[positionIndex + 2] = radius * sinPhi * Math.sin(angle);
  }

  energyParticlePositionAttribute.needsUpdate = true;

  if (energyParticleMaterial) {
    energyParticleMaterial.size = (isMobile ? 0.09 : 0.115) * (1 + pulse * 0.7);
    energyParticleMaterial.opacity = 0.72 + pulse * 0.26;
  }
};

const animate = (time: number) => {
  animationFrame = 0;

  if (
    isDisposed ||
    reducedMotion ||
    !isIntersecting ||
    !isDocumentVisible ||
    !renderer ||
    !scene ||
    !camera ||
    !coreGroup ||
    !orbitGroup
  ) {
    return;
  }

  const isInteractive = isDragging || pulsePhase < 1;
  const targetFps = isMobile
    ? isInteractive ? 30 : 24
    : isInteractive ? 60 : 30;
  const frameInterval = 1000 / targetFps;

  if (lastFrameTime && time - lastFrameTime < frameInterval) {
    animationFrame = requestAnimationFrame(animate);
    return;
  }

  const delta = Math.min((time - (lastFrameTime || time)) / 1000, 0.05);
  const elapsed = time * 0.001;
  lastFrameTime = time;

  if (!isDragging) {
    dragRotationX += dragVelocityX * delta;
    dragRotationY += dragVelocityY * delta;
    const inertia = Math.exp(-delta * 3.6);
    dragVelocityX *= inertia;
    dragVelocityY *= inertia;
  }

  pulsePhase = Math.min(1, pulsePhase + delta * 0.78);
  const pulse = getPulseAmount();

  coreGroup.rotation.x = 0.1 + dragRotationX;
  coreGroup.rotation.y = elapsed * 0.04 + dragRotationY;
  coreGroup.rotation.z = 0.02 + Math.sin(elapsed * 0.3) * 0.018;
  coreGroup.position.y = Math.sin(elapsed * 0.64) * 0.055;
  coreGroup.scale.setScalar(baseResponsiveScale * (1 + pulse * 0.045));

  orbitGroup.rotation.y = elapsed * 0.018 + dragRotationY * 0.16;
  orbitGroup.rotation.x = Math.sin(elapsed * 0.19) * 0.025 + dragRotationX * 0.12;
  orbitGroup.position.y = coreGroup.position.y;
  orbitGroup.scale.setScalar(baseResponsiveScale);

  if (innerCore) {
    innerCore.rotation.y += delta * 0.035;
    innerCore.scale.setScalar(1 + pulse * 0.035);
  }

  if (innerCage) {
    innerCage.rotation.x += delta * 0.24;
    innerCage.rotation.y -= delta * 0.31;
    innerCage.rotation.z += delta * 0.17;
  }

  if (innerSeed) {
    innerSeed.rotation.x -= delta * 0.38;
    innerSeed.rotation.y += delta * 0.46;
    innerSeed.scale.setScalar(1 + pulse * 0.24);
  }

  if (energyHalo) {
    energyHalo.rotation.z -= delta * 0.04;
  }

  if (fresnelMaterial) {
    fresnelMaterial.uniforms.uTime!.value = elapsed;
    fresnelMaterial.uniforms.uPulse!.value = pulse;
  }

  if (pulseMaterial && pulseWaveGroup) {
    const waveActive = pulsePhase < 1;
    pulseWaveGroup.visible = waveActive;
    pulseMaterial.opacity = pulsePhase < 1 ? pulse * 0.42 : 0;
    const pulseScale = 0.72 + pulsePhase * 2.15;
    pulseWaveGroup.scale.setScalar(pulseScale);
    pulseWaveGroup.rotation.x += delta * 0.16;
    pulseWaveGroup.rotation.y -= delta * 0.12;
  }

  if (neuralPointsMaterial) {
    neuralPointsMaterial.size = (isMobile ? 0.027 : 0.032) * (1 + pulse * 0.42);
    neuralPointsMaterial.opacity = 0.74 + pulse * 0.26;
  }

  if (neuralLinkMaterial) {
    neuralLinkMaterial.opacity = 0.2 + pulse * 0.28;
  }

  if (energyBeamMaterial) {
    energyBeamMaterial.opacity = 0.08 + pulse * 0.34;
  }

  if (earthLandMaterial) {
    earthLandMaterial.opacity = 0.86 + pulse * 0.14;
  }

  if (earthGridMaterial) {
    earthGridMaterial.opacity = 0.2 + pulse * 0.24;
  }

  if (coreLight) {
    coreLight.intensity = 3.5 + pulse * 8;
  }

  ringObjects.forEach((ring, index) => {
    const direction = index % 2 === 0 ? 1 : -1;
    ring.rotation.z += delta * (0.04 + index * 0.014) * direction;
  });

  detailObjects.forEach((detail, index) => {
    const direction = index % 2 === 0 ? 1 : -1;
    detail.rotation.x += delta * (0.12 + index * 0.008) * direction;
    detail.rotation.y -= delta * (0.16 + index * 0.006) * direction;
    const baseScale = Number(detail.userData.baseScale || 1);
    detail.scale.setScalar(baseScale * (1 + pulse * 0.32));
  });

  updateEnergyParticles(elapsed, delta, pulse);

  renderer.render(scene, camera);
  animationFrame = requestAnimationFrame(animate);
};

const syncAnimation = () => {
  stopAnimation();

  if (
    !renderer ||
    !scene ||
    !camera ||
    isDisposed ||
    reducedMotion ||
    !isIntersecting ||
    !isDocumentVisible
  ) {
    renderStaticFrame();
    return;
  }

  lastFrameTime = performance.now();
  animationFrame = requestAnimationFrame(animate);
};

const resizeRenderer = () => {
  const element = mountElement.value;

  if (!element || !renderer || !camera || !coreGroup || !orbitGroup) {
    return;
  }

  const width = Math.max(element.clientWidth, 1);
  const height = Math.max(element.clientHeight, 1);
  isMobile = window.matchMedia("(max-width: 767px)").matches;

  camera.aspect = width / height;
  camera.updateProjectionMatrix();

  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, isMobile ? 1 : 1.25));
  renderer.setSize(width, height, false);

  baseResponsiveScale = isMobile
    ? Math.max(1, Math.min(1.12, width / 350))
    : width < 720
      ? 0.9
      : 1;
  coreGroup.scale.setScalar(baseResponsiveScale);
  orbitGroup.scale.setScalar(baseResponsiveScale);

  renderStaticFrame();
};

const triggerPulse = () => {
  hasInteracted.value = true;
  pulsePhase = 0;
  burstEnergyParticles();
  emit("pulse");

  if (reducedMotion) {
    renderStaticFrame();
  } else {
    syncAnimation();
  }
};

const handlePointerDown = (event: PointerEvent) => {
  const element = mountElement.value;

  if (!element || activePointerId !== null) {
    return;
  }

  activePointerId = event.pointerId;
  isDragging = true;
  hasDragged = false;
  isDraggingVisual.value = true;
  pointerDownX = event.clientX;
  pointerDownY = event.clientY;
  lastPointerX = event.clientX;
  lastPointerY = event.clientY;
  dragVelocityX = 0;
  dragVelocityY = 0;
  element.setPointerCapture?.(event.pointerId);
  if (event.pointerType === "mouse") {
    event.preventDefault();
  }
};

const handlePointerMove = (event: PointerEvent) => {
  if (!isDragging || event.pointerId !== activePointerId || reducedMotion) {
    return;
  }

  const deltaX = event.clientX - lastPointerX;
  const deltaY = event.clientY - lastPointerY;
  const totalDistance = Math.hypot(
    event.clientX - pointerDownX,
    event.clientY - pointerDownY
  );

  if (totalDistance > 5) {
    hasDragged = true;
    hasInteracted.value = true;
  }

  dragRotationY += deltaX * 0.008;
  dragRotationX = Math.max(-0.85, Math.min(0.85, dragRotationX + deltaY * 0.006));
  dragVelocityY = deltaX * 0.16;
  dragVelocityX = deltaY * 0.12;
  lastPointerX = event.clientX;
  lastPointerY = event.clientY;
};

const finishPointerInteraction = (event: PointerEvent) => {
  const element = mountElement.value;

  if (!isDragging || event.pointerId !== activePointerId) {
    return;
  }

  isDragging = false;
  isDraggingVisual.value = false;
  activePointerId = null;

  if (element?.hasPointerCapture?.(event.pointerId)) {
    element.releasePointerCapture(event.pointerId);
  }

  if (!hasDragged) {
    triggerPulse();
  }
};

const handlePointerCancel = (event: PointerEvent) => {
  if (event.pointerId !== activePointerId) {
    return;
  }

  isDragging = false;
  isDraggingVisual.value = false;
  activePointerId = null;
};

const handleVisibilityChange = () => {
  isDocumentVisible = !document.hidden;
  syncAnimation();
};

const handleContextLost = (event: Event) => {
  event.preventDefault();
  isReady.value = false;
  stopAnimation();
  emit("unavailable");
};

const removeInteractionListeners = () => {
  const element = mountElement.value;

  element?.removeEventListener("pointerdown", handlePointerDown);
  element?.removeEventListener("pointermove", handlePointerMove);
  element?.removeEventListener("pointerup", finishPointerInteraction);
  element?.removeEventListener("pointercancel", handlePointerCancel);
};

const disposeScene = () => {
  stopAnimation();
  resizeObserver?.disconnect();
  intersectionObserver?.disconnect();
  resizeObserver = null;
  intersectionObserver = null;
  removeInteractionListeners();
  document.removeEventListener("visibilitychange", handleVisibilityChange);

  if (canvas) {
    canvas.removeEventListener("webglcontextlost", handleContextLost);
  }

  const geometries = new Set<BufferGeometry>();
  const materials = new Set<Material>();
  const textures = new Set<Texture>();

  scene?.traverse((object) => {
    const renderable = object as Object3D & {
      geometry?: BufferGeometry;
      material?: Material | Material[];
    };

    if (renderable.geometry) {
      geometries.add(renderable.geometry);
    }

    const objectMaterials = Array.isArray(renderable.material)
      ? renderable.material
      : renderable.material
        ? [renderable.material]
        : [];

    objectMaterials.forEach((material) => {
      materials.add(material);

      Object.values(material).forEach((value) => {
        if (value && typeof value === "object" && "isTexture" in value) {
          textures.add(value as Texture);
        }
      });
    });
  });

  textures.forEach((texture) => texture.dispose());
  materials.forEach((material) => material.dispose());
  geometries.forEach((geometry) => geometry.dispose());

  if (renderer) {
    renderer.setAnimationLoop(null);
    renderer.renderLists.dispose();
    renderer.dispose();
    renderer.forceContextLoss();
  }

  canvas?.remove();
  ringObjects.length = 0;
  detailObjects.length = 0;
  canvas = null;
  renderer = null;
  camera = null;
  scene = null;
  coreGroup = null;
  orbitGroup = null;
  innerCore = null;
  innerCage = null;
  innerSeed = null;
  energyHalo = null;
  pulseWaveGroup = null;
  fresnelMaterial = null;
  pulseMaterial = null;
  neuralPointsMaterial = null;
  neuralLinkMaterial = null;
  energyBeamMaterial = null;
  earthLandMaterial = null;
  earthGridMaterial = null;
  energyParticleGeometry = null;
  energyParticlePositionAttribute = null;
  energyParticleMaterial = null;
  energyParticlePositions = null;
  energyParticleRadii = null;
  energyParticleAngles = null;
  energyParticlePhis = null;
  energyParticleSpeeds = null;
  energyParticleBurstOffsets = null;
  energyParticleBurstVelocities = null;
  coreLight = null;
};

const initializeScene = async () => {
  const element = mountElement.value;

  if (!element || isInitializing || renderer || isDisposed) {
    return;
  }

  isInitializing = true;
  reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  isMobile = window.matchMedia("(max-width: 767px)").matches;
  isDocumentVisible = !document.hidden;

  if (isLowPowerDevice()) {
    isInitializing = false;
    emit("unavailable");
    return;
  }

  try {
    const THREE = await import("three");

    if (isDisposed || !mountElement.value) {
      isInitializing = false;
      return;
    }

    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(34, 1, 0.1, 100);
    camera.position.set(0, 0, 8.05);

    renderer = new THREE.WebGLRenderer({
      alpha: true,
      antialias: false,
      powerPreference: "high-performance",
      premultipliedAlpha: true,
      precision: "mediump",
      stencil: false,
    });
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.08;

    canvas = renderer.domElement;
    canvas.setAttribute("aria-hidden", "true");
    canvas.addEventListener("webglcontextlost", handleContextLost, false);
    element.appendChild(canvas);

    const primary = new THREE.Color(props.primaryColor);
    const glow = new THREE.Color(props.glowColor);

    coreGroup = new THREE.Group();
    orbitGroup = new THREE.Group();
    scene.add(coreGroup, orbitGroup);

    const shellGeometry = new THREE.SphereGeometry(
      1.2,
      isMobile ? 32 : 48,
      isMobile ? 22 : 32
    );
    fresnelMaterial = new THREE.ShaderMaterial({
      uniforms: {
        uPrimary: { value: primary },
        uGlow: { value: glow },
        uTime: { value: 0 },
        uPulse: { value: 0 },
      },
      vertexShader: `
        varying vec3 vWorldNormal;
        varying vec3 vViewDirection;

        void main() {
          vec4 worldPosition = modelMatrix * vec4(position, 1.0);
          vWorldNormal = normalize(mat3(modelMatrix) * normal);
          vViewDirection = normalize(cameraPosition - worldPosition.xyz);
          gl_Position = projectionMatrix * viewMatrix * worldPosition;
        }
      `,
      fragmentShader: `
        uniform vec3 uPrimary;
        uniform vec3 uGlow;
        uniform float uTime;
        uniform float uPulse;
        varying vec3 vWorldNormal;
        varying vec3 vViewDirection;

        void main() {
          float edge = 1.0 - abs(dot(normalize(vWorldNormal), normalize(vViewDirection)));
          float fresnel = pow(edge, 2.1);
          float scan = 0.5 + 0.5 * sin(vWorldNormal.y * 24.0 - uTime * 1.15);
          float alpha = fresnel * (0.48 + uPulse * 0.35) + scan * 0.018;
          vec3 color = mix(uPrimary, uGlow, clamp(fresnel + scan * 0.16, 0.0, 1.0));
          gl_FragColor = vec4(color, alpha);
        }
      `,
      transparent: true,
      depthWrite: false,
      side: THREE.DoubleSide,
      blending: THREE.AdditiveBlending,
    });
    const shell = new THREE.Mesh(shellGeometry, fresnelMaterial);
    shell.scale.setScalar(1.035);
    shell.renderOrder = 6;
    coreGroup.add(shell);

    const globeMaterial = new THREE.MeshStandardMaterial({
      color: new THREE.Color("#020d24"),
      emissive: new THREE.Color("#04245d"),
      emissiveIntensity: 0.68,
      metalness: 0.08,
      roughness: 0.62,
      transparent: true,
      opacity: 0.84,
      depthWrite: true,
    });
    const globe = new THREE.Mesh(shellGeometry, globeMaterial);
    globe.renderOrder = 1;
    coreGroup.add(globe);

    innerCore = new THREE.Group();
    innerCore.rotation.set(0.04, -1.92, -0.025);
    coreGroup.add(innerCore);

    const earthTexture = new THREE.CanvasTexture(createDigitalEarthTexture(isMobile));
    earthTexture.colorSpace = THREE.SRGBColorSpace;
    earthTexture.anisotropy = Math.min(4, renderer.capabilities.getMaxAnisotropy());
    earthLandMaterial = new THREE.MeshBasicMaterial({
      map: earthTexture,
      color: new THREE.Color("#b7f8ff"),
      transparent: true,
      opacity: 0.86,
      blending: THREE.AdditiveBlending,
      depthWrite: false,
    });
    const landSurface = new THREE.Mesh(shellGeometry, earthLandMaterial);
    landSurface.scale.setScalar(1.008);
    landSurface.renderOrder = 5;
    innerCore.add(landSurface);

    earthGridMaterial = new THREE.LineBasicMaterial({
      color: new THREE.Color("#59dfff"),
      transparent: true,
      opacity: 0.2,
      blending: THREE.AdditiveBlending,
      depthWrite: false,
      depthTest: true,
    });
    const gridRadius = 1.216;
    const gridSegments = isMobile ? 54 : 80;

    for (let latitude = -60; latitude <= 60; latitude += 20) {
      const latitudeRadians = THREE.MathUtils.degToRad(latitude);
      const latitudePoints: InstanceType<typeof THREE.Vector3>[] = [];

      for (let segment = 0; segment < gridSegments; segment += 1) {
        const longitudeRadians = (segment / gridSegments) * Math.PI * 2;
        const horizontalRadius = gridRadius * Math.cos(latitudeRadians);
        latitudePoints.push(new THREE.Vector3(
          horizontalRadius * Math.cos(longitudeRadians),
          gridRadius * Math.sin(latitudeRadians),
          horizontalRadius * Math.sin(longitudeRadians)
        ));
      }

      const latitudeGeometry = new THREE.BufferGeometry().setFromPoints(latitudePoints);
      const latitudeLine = new THREE.LineLoop(latitudeGeometry, earthGridMaterial);
      latitudeLine.renderOrder = 4;
      innerCore.add(latitudeLine);
    }

    for (let longitude = 0; longitude < 180; longitude += 20) {
      const longitudeRadians = THREE.MathUtils.degToRad(longitude);
      const longitudePoints: InstanceType<typeof THREE.Vector3>[] = [];

      for (let segment = 0; segment < gridSegments; segment += 1) {
        const angle = (segment / gridSegments) * Math.PI * 2;
        longitudePoints.push(new THREE.Vector3(
          gridRadius * Math.sin(angle) * Math.cos(longitudeRadians),
          gridRadius * Math.cos(angle),
          gridRadius * Math.sin(angle) * Math.sin(longitudeRadians)
        ));
      }

      const longitudeGeometry = new THREE.BufferGeometry().setFromPoints(longitudePoints);
      const longitudeLine = new THREE.LineLoop(longitudeGeometry, earthGridMaterial);
      longitudeLine.renderOrder = 4;
      innerCore.add(longitudeLine);
    }

    const equatorMaterial = new THREE.LineBasicMaterial({
      color: new THREE.Color("#c8fbff"),
      transparent: true,
      opacity: 0.58,
      blending: THREE.AdditiveBlending,
      depthWrite: false,
    });
    const equatorPoints: InstanceType<typeof THREE.Vector3>[] = [];
    for (let segment = 0; segment < gridSegments; segment += 1) {
      const angle = (segment / gridSegments) * Math.PI * 2;
      equatorPoints.push(new THREE.Vector3(
        gridRadius * Math.cos(angle),
        0,
        gridRadius * Math.sin(angle)
      ));
    }
    const equator = new THREE.LineLoop(
      new THREE.BufferGeometry().setFromPoints(equatorPoints),
      equatorMaterial
    );
    equator.renderOrder = 5;
    innerCore.add(equator);

    const geodesicGeometry = new THREE.IcosahedronGeometry(
      1.225,
      isMobile ? 2 : 3
    );
    const geodesicMaterial = new THREE.MeshBasicMaterial({
      color: new THREE.Color("#4bcfff"),
      transparent: true,
      opacity: isMobile ? 0.1 : 0.15,
      wireframe: true,
      blending: THREE.AdditiveBlending,
      depthWrite: false,
      depthTest: true,
    });
    const geodesicMesh = new THREE.Mesh(geodesicGeometry, geodesicMaterial);
    geodesicMesh.rotation.set(0.12, -0.2, 0.08);
    geodesicMesh.renderOrder = 3;
    innerCore.add(geodesicMesh);

    const innerGlow = new THREE.Mesh(
      new THREE.SphereGeometry(0.84, isMobile ? 20 : 28, isMobile ? 14 : 20),
      new THREE.MeshBasicMaterial({
        color: new THREE.Color("#0755c9"),
        transparent: true,
        opacity: 0.09,
        side: THREE.BackSide,
        blending: THREE.AdditiveBlending,
        depthWrite: false,
      })
    );
    innerGlow.renderOrder = 2;
    coreGroup.add(innerGlow);

    const neuralNodeCount = isMobile ? 34 : 72;
    const neuralPositions: number[] = [];
    const neuralVectors: InstanceType<typeof THREE.Vector3>[] = [];
    const goldenAngle = Math.PI * (3 - Math.sqrt(5));

    for (let index = 0; index < neuralNodeCount; index += 1) {
      const y = 1 - (index / Math.max(neuralNodeCount - 1, 1)) * 2;
      const radius = Math.sqrt(Math.max(0, 1 - y * y));
      const theta = goldenAngle * index;
      const vector = new THREE.Vector3(
        Math.cos(theta) * radius,
        y,
        Math.sin(theta) * radius
      ).multiplyScalar(1.205);
      neuralVectors.push(vector);
      neuralPositions.push(vector.x, vector.y, vector.z);
    }

    const neuralGeometry = new THREE.BufferGeometry();
    neuralGeometry.setAttribute(
      "position",
      new THREE.Float32BufferAttribute(neuralPositions, 3)
    );
    neuralPointsMaterial = new THREE.PointsMaterial({
      color: glow,
      size: isMobile ? 0.027 : 0.032,
      transparent: true,
      opacity: 0.74,
      sizeAttenuation: true,
      depthWrite: false,
      blending: THREE.AdditiveBlending,
    });
    const neuralPoints = new THREE.Points(neuralGeometry, neuralPointsMaterial);
    neuralPoints.renderOrder = 5;
    innerCore.add(neuralPoints);

    const neuralLinks: number[] = [];
    neuralVectors.forEach((source, sourceIndex) => {
      const nearest = neuralVectors
        .map((target, targetIndex) => ({
          target,
          targetIndex,
          distance: source.distanceTo(target),
        }))
        .filter(({ targetIndex }) => targetIndex > sourceIndex)
        .sort((a, b) => a.distance - b.distance)
        .slice(0, 3);

      nearest.forEach(({ target, distance }) => {
        if (distance < 0.86) {
          neuralLinks.push(source.x, source.y, source.z, target.x, target.y, target.z);
        }
      });
    });

    const neuralLinkGeometry = new THREE.BufferGeometry();
    neuralLinkGeometry.setAttribute(
      "position",
      new THREE.Float32BufferAttribute(neuralLinks, 3)
    );
    neuralLinkMaterial = new THREE.LineBasicMaterial({
      color: primary,
      transparent: true,
      opacity: 0.3,
      depthWrite: false,
      blending: THREE.AdditiveBlending,
    });
    const neuralLines = new THREE.LineSegments(neuralLinkGeometry, neuralLinkMaterial);
    neuralLines.renderOrder = 4;
    innerCore.add(neuralLines);

    const beamPositions: number[] = [];
    const beamNodeIndexes = isMobile
      ? [1, 6, 11, 17, 22]
      : [1, 5, 9, 13, 18, 23, 28, 32];

    beamNodeIndexes.forEach((nodeIndex) => {
      const endpoint = neuralVectors[nodeIndex];

      if (!endpoint) {
        return;
      }

      const origin = endpoint.clone().multiplyScalar(0.43);
      beamPositions.push(
        origin.x,
        origin.y,
        origin.z,
        endpoint.x,
        endpoint.y,
        endpoint.z
      );
    });

    const beamGeometry = new THREE.BufferGeometry();
    beamGeometry.setAttribute(
      "position",
      new THREE.Float32BufferAttribute(beamPositions, 3)
    );
    energyBeamMaterial = new THREE.LineBasicMaterial({
      color: glow,
      transparent: true,
      opacity: 0.14,
      depthWrite: false,
      blending: THREE.AdditiveBlending,
    });
    const energyBeams = new THREE.LineSegments(beamGeometry, energyBeamMaterial);
    energyBeams.renderOrder = 2;
    innerCore.add(energyBeams);

    const shardGeometry = new THREE.TetrahedronGeometry(isMobile ? 0.052 : 0.064, 0);
    const shardMaterial = new THREE.MeshStandardMaterial({
      color: new THREE.Color("#bcefff"),
      emissive: glow,
      emissiveIntensity: 1.3,
      metalness: 0.32,
      roughness: 0.22,
      flatShading: true,
    });
    const shardCount = isMobile ? 4 : 6;

    for (let index = 0; index < shardCount; index += 1) {
      const phi = Math.acos(1 - 2 * ((index + 0.5) / shardCount));
      const theta = goldenAngle * index + 0.55;
      const radius = 1.42 + (index % 3) * 0.055;
      const shard = new THREE.Mesh(shardGeometry, shardMaterial);
      shard.position.set(
        radius * Math.sin(phi) * Math.cos(theta),
        radius * Math.cos(phi),
        radius * Math.sin(phi) * Math.sin(theta)
      );
      shard.userData.baseScale = 0.78 + (index % 3) * 0.16;
      shard.scale.setScalar(shard.userData.baseScale);
      shard.rotation.set(theta * 0.3, phi * 0.45, theta * 0.2);
      detailObjects.push(shard);
      coreGroup.add(shard);
    }

    const haloMaterial = new THREE.MeshBasicMaterial({
      color: new THREE.Color("#18b7da"),
      transparent: true,
      opacity: 0.2,
      side: THREE.DoubleSide,
      blending: THREE.NormalBlending,
      depthWrite: false,
      depthTest: true,
    });
    energyHalo = new THREE.Mesh(
      new THREE.RingGeometry(1.47, 1.476, isMobile ? 72 : 112),
      haloMaterial
    );
    energyHalo.rotation.set(0.72, 0.18, -0.12);
    energyHalo.renderOrder = 6;
    orbitGroup.add(energyHalo);

    pulseMaterial = new THREE.MeshBasicMaterial({
      color: glow,
      transparent: true,
      opacity: 0,
      side: THREE.DoubleSide,
      blending: THREE.AdditiveBlending,
      depthWrite: false,
    });
    const pulseGeometry = new THREE.RingGeometry(1.23, 1.26, isMobile ? 48 : 80);
    const pulseRotations = [
      [0, 0, 0],
      [1.08, 0.34, 0.42],
      [0.52, 1.14, -0.38],
    ] as const;
    pulseWaveGroup = new THREE.Group();
    pulseWaveGroup.visible = false;

    pulseRotations.forEach((rotation) => {
      const pulseMesh = new THREE.Mesh(pulseGeometry, pulseMaterial!);
      pulseMesh.rotation.set(rotation[0], rotation[1], rotation[2]);
      pulseMesh.renderOrder = 8;
      pulseWaveGroup?.add(pulseMesh);
    });

    orbitGroup.add(pulseWaveGroup);

    const ringSettings = [
      {
        radius: 1.56,
        tube: 0.008,
        opacity: 0.72,
        color: new THREE.Color("#1979ee"),
        rotation: [1.06, 0.16, 0.16],
        accentStart: 0.18,
        accentArc: Math.PI * 0.48,
        nodes: 3,
      },
      {
        radius: 1.91,
        tube: 0.006,
        opacity: 0.56,
        color: new THREE.Color("#16aeca"),
        rotation: [0.42, 0.94, 0.68],
        accentStart: 2.28,
        accentArc: Math.PI * 0.34,
        nodes: 2,
      },
      {
        radius: 2.22,
        tube: 0.0045,
        opacity: 0.42,
        color: new THREE.Color("#6859d7"),
        rotation: [0.78, -0.5, 1.16],
        accentStart: 4.05,
        accentArc: Math.PI * 0.25,
        nodes: 2,
      },
    ] as const;
    const nodeGeometry = new THREE.SphereGeometry(isMobile ? 0.016 : 0.021, 8, 8);
    const nodeHaloGeometry = new THREE.SphereGeometry(isMobile ? 0.03 : 0.038, 8, 8);
    const tickGeometry = new THREE.BoxGeometry(
      isMobile ? 0.045 : 0.06,
      isMobile ? 0.007 : 0.009,
      isMobile ? 0.007 : 0.009
    );

    ringSettings.forEach((settings, index) => {
      const ringGroup = new THREE.Group();
      ringGroup.rotation.set(
        settings.rotation[0],
        settings.rotation[1],
        settings.rotation[2]
      );

      const rearTrace = new THREE.Mesh(
        new THREE.TorusGeometry(
          settings.radius,
          settings.tube * 1.75,
          8,
          isMobile ? 72 : 112
        ),
        new THREE.MeshBasicMaterial({
          color: settings.color,
          transparent: true,
          opacity: 0.08,
          blending: THREE.NormalBlending,
          depthWrite: false,
          depthTest: false,
        })
      );
      rearTrace.renderOrder = 5;
      ringGroup.add(rearTrace);

      const ring = new THREE.Mesh(
        new THREE.TorusGeometry(
          settings.radius,
          settings.tube,
          8,
          isMobile ? 72 : 112
        ),
        new THREE.MeshBasicMaterial({
          color: settings.color,
          transparent: true,
          opacity: settings.opacity,
          blending: THREE.NormalBlending,
          depthWrite: false,
          depthTest: true,
        })
      );
      ring.renderOrder = 7;
      ringGroup.add(ring);

      const accent = new THREE.Mesh(
        new THREE.TorusGeometry(
          settings.radius,
          settings.tube * 1.85,
          8,
          isMobile ? 32 : 48,
          settings.accentArc
        ),
        new THREE.MeshBasicMaterial({
          color: settings.color,
          transparent: true,
          opacity: Math.min(0.92, settings.opacity + 0.18),
          blending: THREE.NormalBlending,
          depthWrite: false,
          depthTest: true,
        })
      );
      accent.rotation.z = settings.accentStart;
      accent.renderOrder = 8;
      ringGroup.add(accent);

      for (let tickIndex = 0; tickIndex < (isMobile ? 4 : 7); tickIndex += 1) {
        const tickAngle = (tickIndex / (isMobile ? 4 : 7)) * Math.PI * 2 + index * 0.31;
        const tick = new THREE.Mesh(
          tickGeometry,
          new THREE.MeshBasicMaterial({
            color: settings.color,
            transparent: true,
            opacity: 0.38 + (tickIndex % 3) * 0.08,
            blending: THREE.NormalBlending,
            depthWrite: false,
            depthTest: true,
          })
        );
        tick.position.set(
          Math.cos(tickAngle) * settings.radius,
          Math.sin(tickAngle) * settings.radius,
          0
        );
        tick.rotation.z = tickAngle + Math.PI / 2;
        tick.renderOrder = 8;
        ringGroup.add(tick);
      }

      for (let nodeIndex = 0; nodeIndex < settings.nodes; nodeIndex += 1) {
        const nodeAngle = settings.accentStart +
          (nodeIndex / Math.max(settings.nodes - 1, 1)) * settings.accentArc;
        const node = new THREE.Mesh(
          nodeGeometry,
          new THREE.MeshBasicMaterial({
            color: settings.color,
            transparent: true,
            opacity: 0.95,
            blending: THREE.NormalBlending,
            depthWrite: false,
            depthTest: true,
          })
        );
        node.position.set(
          Math.cos(nodeAngle) * settings.radius,
          Math.sin(nodeAngle) * settings.radius,
          0
        );
        node.renderOrder = 9;
        ringGroup.add(node);

        const nodeHalo = new THREE.Mesh(
          nodeHaloGeometry,
          new THREE.MeshBasicMaterial({
            color: settings.color,
            transparent: true,
            opacity: 0.12,
            blending: THREE.NormalBlending,
            depthWrite: false,
            depthTest: true,
          })
        );
        nodeHalo.position.copy(node.position);
        nodeHalo.renderOrder = 8;
        ringGroup.add(nodeHalo);
      }

      ringObjects.push(ringGroup);
      orbitGroup?.add(ringGroup);
    });

    const particleCount = isMobile ? 30 : 60;
    const positions = new Float32Array(particleCount * 3);
    const particleColors = new Float32Array(particleCount * 3);
    const particleRadii = new Float32Array(particleCount);
    const particleAngles = new Float32Array(particleCount);
    const particlePhis = new Float32Array(particleCount);
    const particleSpeeds = new Float32Array(particleCount);
    const particleBurstOffsets = new Float32Array(particleCount);
    const particleBurstVelocities = new Float32Array(particleCount);
    let seed = 7823;
    const random = () => {
      seed = (seed * 9301 + 49297) % 233280;
      return seed / 233280;
    };

    for (let index = 0; index < particleCount; index += 1) {
      const radius = 2.35 + random() * 0.85;
      const theta = random() * Math.PI * 2;
      const phi = Math.acos(2 * random() - 1);
      particleRadii[index] = radius;
      particleAngles[index] = theta;
      particlePhis[index] = phi;
      particleSpeeds[index] = (0.16 + random() * 0.22) * (index % 2 === 0 ? 1 : -1);
      positions[index * 3] = radius * Math.sin(phi) * Math.cos(theta);
      positions[index * 3 + 1] = radius * Math.cos(phi);
      positions[index * 3 + 2] = radius * Math.sin(phi) * Math.sin(theta);

      const color = index % 7 === 0
        ? new THREE.Color("#7567ff")
        : index % 2 === 0
          ? glow
          : primary;
      particleColors[index * 3] = color.r;
      particleColors[index * 3 + 1] = color.g;
      particleColors[index * 3 + 2] = color.b;
    }

    energyParticlePositions = positions;
    energyParticleRadii = particleRadii;
    energyParticleAngles = particleAngles;
    energyParticlePhis = particlePhis;
    energyParticleSpeeds = particleSpeeds;
    energyParticleBurstOffsets = particleBurstOffsets;
    energyParticleBurstVelocities = particleBurstVelocities;
    energyParticleGeometry = new THREE.BufferGeometry();
    energyParticlePositionAttribute = new THREE.BufferAttribute(positions, 3);
    energyParticleGeometry.setAttribute("position", energyParticlePositionAttribute);
    energyParticleGeometry.setAttribute("color", new THREE.BufferAttribute(particleColors, 3));
    const particleSprite = document.createElement("canvas");
    particleSprite.width = 64;
    particleSprite.height = 64;
    const particleSpriteContext = particleSprite.getContext("2d");

    if (particleSpriteContext) {
      const particleGradient = particleSpriteContext.createRadialGradient(32, 32, 0, 32, 32, 32);
      particleGradient.addColorStop(0, "rgba(255, 255, 255, 1)");
      particleGradient.addColorStop(0.18, "rgba(160, 240, 255, 0.95)");
      particleGradient.addColorStop(0.48, "rgba(53, 214, 255, 0.42)");
      particleGradient.addColorStop(1, "rgba(53, 214, 255, 0)");
      particleSpriteContext.fillStyle = particleGradient;
      particleSpriteContext.fillRect(0, 0, 64, 64);
    }

    const particleTexture = new THREE.CanvasTexture(particleSprite);
    energyParticleMaterial = new THREE.PointsMaterial({
      size: isMobile ? 0.09 : 0.115,
      transparent: true,
      opacity: 0.72,
      sizeAttenuation: true,
      depthWrite: false,
      vertexColors: true,
      map: particleTexture,
      blending: THREE.AdditiveBlending,
    });
    const energyParticles = new THREE.Points(
      energyParticleGeometry,
      energyParticleMaterial
    );
    energyParticles.renderOrder = 7;
    orbitGroup.add(energyParticles);

    scene.add(new THREE.HemisphereLight("#dff7ff", "#061738", 1.05));

    const keyLight = new THREE.DirectionalLight("#ffffff", 1.45);
    keyLight.position.set(3.8, 4.2, 5.5);
    scene.add(keyLight);

    const blueLight = new THREE.PointLight(primary, 4.5, 13, 2);
    blueLight.position.set(-2.8, -1.2, 3.2);
    scene.add(blueLight);

    const cyanLight = new THREE.PointLight(glow, 3.8, 12, 2);
    cyanLight.position.set(3, 1.8, 2.4);
    scene.add(cyanLight);

    coreLight = new THREE.PointLight(glow, 3.5, 4.5, 2);
    coreLight.position.set(0, 0, 0.65);
    scene.add(coreLight);

    resizeObserver = new ResizeObserver(resizeRenderer);
    resizeObserver.observe(element);

    intersectionObserver = new IntersectionObserver(
      ([entry]) => {
        isIntersecting = entry?.isIntersecting ?? true;
        syncAnimation();
      },
      { rootMargin: "120px 0px", threshold: 0.02 }
    );
    intersectionObserver.observe(element);

    element.addEventListener("pointerdown", handlePointerDown);
    element.addEventListener("pointermove", handlePointerMove, { passive: true });
    element.addEventListener("pointerup", finishPointerInteraction);
    element.addEventListener("pointercancel", handlePointerCancel);
    document.addEventListener("visibilitychange", handleVisibilityChange);

    resizeRenderer();
    renderStaticFrame();
    isReady.value = true;
    emit("ready");
    syncAnimation();
  } catch {
    disposeScene();
    emit("unavailable");
  } finally {
    isInitializing = false;
  }
};

onMounted(() => {
  initializeScene();
});

onBeforeUnmount(() => {
  isDisposed = true;
  isReady.value = false;
  disposeScene();
});
</script>

<style scoped>
.technology-core {
  position: absolute;
  z-index: 4;
  inset: 0;
  overflow: hidden;
  opacity: 0;
  pointer-events: none;
  touch-action: pan-y;
  transition: opacity 500ms ease;
  user-select: none;
  -webkit-user-select: none;
}

.technology-core--ready {
  opacity: 1;
  cursor: grab;
  pointer-events: auto;
}

.technology-core--dragging {
  cursor: grabbing;
}

.technology-core__hint {
  position: absolute;
  z-index: 3;
  bottom: 5.5%;
  left: 50%;
  display: inline-flex;
  align-items: center;
  gap: 0.55rem;
  color: rgba(34, 84, 129, 0.72);
  font-family: var(--font-tech, sans-serif);
  font-size: 0.63rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  line-height: 1;
  text-transform: uppercase;
  transform: translateX(-50%);
  transition: opacity 220ms ease, transform 220ms ease;
  white-space: nowrap;
}

.technology-core__hint::before,
.technology-core__hint::after {
  width: 1.5rem;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(8, 102, 255, 0.34));
  content: "";
}

.technology-core__hint::after {
  background: linear-gradient(90deg, rgba(8, 102, 255, 0.34), transparent);
}

.technology-core__hint-icon {
  color: var(--tech-hero-primary, #0866ff);
  font-size: 0.9rem;
  letter-spacing: 0;
}

.technology-core__hint-text--mobile {
  display: none;
}

.technology-core--interacted .technology-core__hint {
  opacity: 0;
  transform: translate(-50%, 0.35rem);
}

.technology-core :deep(canvas) {
  position: absolute;
  z-index: 1;
  inset: 0;
  display: block;
  width: 100%;
  height: 100%;
  outline: none;
  pointer-events: none;
}

@media (max-width: 767px) {
  .technology-core__hint {
    bottom: 1.5%;
    max-width: calc(100% - 2rem);
    font-size: 0.52rem;
    letter-spacing: 0.065em;
    white-space: normal;
  }

  .technology-core__hint::before,
  .technology-core__hint::after {
    width: 0.75rem;
  }

  .technology-core__hint-text--desktop {
    display: none;
  }

  .technology-core__hint-text--mobile {
    display: inline;
    text-align: center;
  }
}

@media (prefers-reduced-motion: reduce) {
  .technology-core,
  .technology-core__hint {
    transition: none;
  }
}
</style>
