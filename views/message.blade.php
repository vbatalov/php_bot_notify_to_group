@php
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
@endphp
{{$data['text']}}

{{$tags_string ?? "Empty tags"}}