<?php
/**
 * Created by PhpStorm.
 * User: hunostor
 * Date: 2017.09.12.
 * Time: 11:43
 */


function helper(string $html): array
{
    $split = explode('{{', $html);
    $count = count($split);

    $placeholders = [];

    for ($x = 1; $x < $count; $x ++)
    {
        $splitArr =  explode('}}', $split[$x]);
        $placeholder = $splitArr[0];
        $placeholders[] = trim($placeholder);
    };

    return $placeholders;
}

function template(array $datas, string $html): string
{
    $split = explode('{{', $html);
    $count = count($split);

    for ($x = 1; $x < $count; $x ++)
    {
        $splitArr =  explode('}}', $split[$x]);
        $placeholder = $splitArr[0];
        $key = trim($placeholder);

        // if file url & add htmlParts
        if (fileUrl($key))
        {
            $htmlParts = file_get_contents(fileUrl($key));
            $html   = str_replace('{{' . $placeholder . '}}', $htmlParts,  $html);
        }

        if (array_key_exists($key, $datas))
        {
            $html   = str_replace('{{' . $placeholder . '}}', $datas[$key],  $html);
        }

    };

    return $html;
}

function fileUrl($placeholder)
{
    $pattern = '/^@include:/';

    if (preg_match($pattern, $placeholder))
    {
        $rawUrl = substr($placeholder, 9);
        $url = trim($rawUrl);
        $url = substr($url, 1, -1);
        return $url;
    } else {
        return false;
    }
}