<?php
    if (isset($data['tags'])) {
       $tags = explode(",", $data['tags']);
        $tags_string = "";

        $count_tags = count($tags);
        $i = 0;
        foreach ($tags as $tag) {
            $i++;

            $filter_tag = trim($tag); // Убрать повторяющиеся проблемы
            $filter_tag = str_replace(" ", "_", $filter_tag); // Заменить пробелы в теге на знак "_"

            $tags_string .= "#$filter_tag ";
        }
    }
?>
<?php echo e($data['text']); ?>


<?php echo e($tags_string ?? "Empty tags"); ?><?php /**PATH D:\BatalovVA\phpstorm\kwork\clean_php_bot_notify_to_group\views/message.blade.php ENDPATH**/ ?>