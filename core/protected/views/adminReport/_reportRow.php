<tr<?php echo (isset($parameters['headerRow']) && $parameters['headerRow']) ? ' class="subtitle2"' : ''?>>
    <td style="width:80%"><?php echo $parameters['label']; ?></td>
    <td style="width:20%; text-align:right;">
        <?php
        if(isset($link) && $parameters['value'] != 0 && $link != ''){
            echo '<a href="/adminReport/demographic/startDate/'.$startDate.'/endDate/'.$endDate.'/request/'.$link.'">';
            echo $parameters['value'].'</a>';
        }else
            echo $parameters['value'];
        ?>
    </td>
</tr>