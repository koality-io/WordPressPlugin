<?php

$scores = [
    'Uptime' => 100,
    'Performance' => 100,
    'SEO' => 100,
    'Content' => 100,
    'Tech' => 100,
    'Security' => 100
]

?>

<table>
    <?php foreach ($scores as $key => $score): ?>
        <tr>
            <td width="200px"><?php echo $key ?></td>
            <td><?php echo $score ?> / 100</td>
        </tr>
    <?php endforeach; ?>
</table>
