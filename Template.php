<?php
/**
 * Created by PhpStorm.
 * User: hunostor
 * Date: 2017.09.13.
 */

class Template
{
    private $html;

    private $fileIncludePattern = '/^@include:/';

    private $datas;

    private $template;

    /**
     * Template constructor.
     * @param $fileContents
     */
    public function __construct($fileContents, array $datas)
    {
        $this->html = file_get_contents($fileContents);
        $this->datas = $datas;
        $this->template = $this->unionTemplateFiles($this->html);
    }

    private function escaping($string)
    {
        return htmlspecialchars($string);
    }

    private function getRawPlaceholders($html)
    {
        $split = explode('{{', $html);
        $count = count($split);

        $rawPlaceholders = [];

        for ($x = 1; $x < $count; $x ++)
        {
            $splitArr =  explode('}}', $split[$x]);
            $rawPlaceholders[] = $splitArr[0];
        };

        return $rawPlaceholders;
    }

    private function fileUrl($placeholder)
    {
        $pattern = $this->fileIncludePattern;

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

    private function unionTemplateFiles($html)
    {
        $split = explode('{{', $html);
        $count = count($split);

        for ($x = 1; $x < $count; $x ++)
        {
            $splitArr = explode('}}', $split[$x]);
            $placeholder = $splitArr[0];
            $key = trim($placeholder);

            // if file url & add htmlParts
            if ($this->fileUrl($key))
            {
                $htmlParts = file_get_contents($this->fileUrl($key));
                $htmlParts = $this->unionTemplateFiles($htmlParts);
                $html  = str_replace('{{' . $placeholder . '}}', $htmlParts,  $html);
            }
        };

        return $html;
    }

    private function templateDataDrive(array $datas)
    {
        extract($datas);

        $split = explode('{{', $this->template);
        $count = count($split);

        for ($x = 1; $x < $count; $x ++)
        {
            $splitArr =  explode('}}', $split[$x]);
            $placeholder = $splitArr[0];
            $key = trim($placeholder);

            if ($objPlaceholder = $this->objectPlaceholder($key))
            {
                $this->template   = str_replace('{{' . $placeholder . '}}',
                    $this->escaping( ${$objPlaceholder[0]}->{$objPlaceholder[1]} ),
                    $this->template
                );
            }


            if (array_key_exists($key, $datas))
            {
                $this->template   = str_replace('{{' . $placeholder . '}}',
                    $this->escaping(${$key}),
                    $this->template
                );
            }
        };
    }

    public function objectPlaceholder($placeholder)
    {
        $objectBoundaryPattern = '/\./';

        if (preg_match($objectBoundaryPattern, $placeholder))
        {
            return explode('.', $placeholder);
        }

        return null;
    }

    public function render()
    {
        $this->templateDataDrive($this->datas);

        echo $this->template;
    }
}