<?php
// Avatar helper - Generates SVG avatars on-the-fly
function getAvatarSvg($number) {
    $colors = [
        1 => ['bg' => '#3B82F6', 'text' => '😊'],   // Blue - Happy
        2 => ['bg' => '#EF4444', 'text' => '🐱'],   // Red - Cat
        3 => ['bg' => '#10B981', 'text' => '🤖'],   // Green - Robot
        4 => ['bg' => '#8B5CF6', 'text' => '🚀'],   // Purple - Rocket
        5 => ['bg' => '#F59E0B', 'text' => '⭐'],   // Orange - Star
        6 => ['bg' => '#EC4899', 'text' => '🎮'],   // Pink - Game
        7 => ['bg' => '#06B6D4', 'text' => '🎨'],   // Cyan - Art
        8 => ['bg' => '#84CC16', 'text' => '🎵'],   // Lime - Music
        9 => ['bg' => '#F97316', 'text' => '⚡'],   // Orange - Lightning
        10 => ['bg' => '#6366F1', 'text' => '🌟'], // Indigo - Sparkle
        11 => ['bg' => '#14B8A6', 'text' => '🔥'], // Teal - Fire
        12 => ['bg' => '#A855F7', 'text' => '💎'], // Purple - Diamond
        13 => ['bg' => '#EAB308', 'text' => '🎯'], // Yellow - Target
        14 => ['bg' => '#22C55E', 'text' => '🌈'], // Green - Rainbow
        15 => ['bg' => '#DC2626', 'text' => '❤️'],  // Red - Heart
    ];
    
    $avatar = $colors[$number] ?? $colors[1];
    
    return "data:image/svg+xml;base64," . base64_encode("
        <svg xmlns='http://www.w3.org/2000/svg' width='150' height='150'>
            <circle cx='75' cy='75' r='75' fill='{$avatar['bg']}'/>
            <text x='75' y='95' font-size='60' text-anchor='middle'>{$avatar['text']}</text>
        </svg>
    ");
}

function getAvatarUrl($avatarNumber) {
    return getAvatarSvg($avatarNumber);
}
?>
