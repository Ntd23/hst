<?php
namespace App\Shortcode\Core;

class ShortcodeParser
{
    public function getAllShortcodeAttributes(string $content): array
    {
        // Cho phép shortcode có tham số, không có tham số, và có nội dung lồng nhau
        preg_match_all('/\[([a-zA-Z0-9\-_]+)(?:\s+([^\]]*?))?\](?:(.*?)\[\/\1\])?/s', $content, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $name = $match[1];
            $attrString = $match[2] ?? '';
            $innerContent = $match[3] ?? '';
            
            $attrs = $this->parseShortcodeAttributeString($attrString);
            
            // Nếu có nội dung lồng nhau, thêm vào attributes
            if ($innerContent !== '') {
                $attrs['content'] = trim($innerContent);
            }
            
            // Trả về mảng tuần tự để giữ thứ tự shortcode và không bị ghi đè trùng tên
            $result[] = [
                'name' => $name,
                'attrs' => $attrs,
            ];
        }
        return $result;
    }

    public function parseShortcodeAttributeString(string $text): array
    {
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        $attributes = [];

        // Pattern giống hệt ShortcodeCompiler::parseAttributes()
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

        if (preg_match_all($pattern, preg_replace('/[\x{00a0}\x{200b}]+/u', ' ', $text), $match, PREG_SET_ORDER)) {
            foreach ($match as $item) {
                if (!empty($item[1])) {
                    $attributes[strtolower($item[1])] = stripcslashes($item[2]);
                } elseif (!empty($item[3])) {
                    $attributes[strtolower($item[3])] = stripcslashes($item[4]);
                } elseif (!empty($item[5])) {
                    $attributes[strtolower($item[5])] = stripcslashes($item[6]);
                } elseif (isset($item[7]) && strlen($item[7])) {
                    $attributes[] = stripcslashes($item[7]);
                } elseif (isset($item[8])) {
                    $attributes[] = stripcslashes($item[8]);
                }
            }
        }

        return $attributes;
    }
}