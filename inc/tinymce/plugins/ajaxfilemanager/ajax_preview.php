<?php
    /**
     * ajax preview
     * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
     * @link www.phpletter.com
     * @since 22/April/2007
     *
     */

    ## OUTPUT BUFFER START ##
    include_once("../../../buffer.php");
    ## INCLUDES ##
    include_once(basePath."/inc/debugger.php");
    include_once(basePath."/inc/config.php");
    include_once(basePath."/inc/common.php");
    ## SETTINGS ##
    if(!(permission("downloads") || permission("news") || permission('artikel'))) {
        die('Permission denied');
    }

    include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
    echo getRealPath($_GET['path']);
    if(!empty($_GET['path']) && file_exists($_GET['path']) && is_file($_GET['path']))
    {

        include_once(CLASS_MANAGER);
        $manager = new manager($_GET['path'], false);
        $fileTypes = $manager->getFileType(basename($_GET['path']));
        if($fileTypes['preview'])
        {
            switch($fileTypes['fileType'])
            {
                case "image":
                    $imageInfo = @getimagesize($_GET['path']);
                    if(!empty($imageInfo[0]) && !empty($imageInfo[1]))
                    {
                        $thumInfo = getThumbWidthHeight($imageInfo[0], $imageInfo[1], 400, 135);
                        printf("<img src=\"%s\" width=\"%s\" height=\"%s\" />", getFileUrl($_GET['path']), $thumInfo['width'], $thumInfo['height']);

                    }else
                    {
                        echo PREVIEW_IMAGE_LOAD_FAILED;
                    }

                    break;
                case "txt":
                    if(($fp = @fopen($_GET['path'], 'r')))
                    {
                        echo @fread($fp, @filesize($_GET['path']));
                        @fclose($fp);
                    }else
                    {
                        echo PREVIEW_OPEN_FAILED . ".";
                    }
                    break;

                case "video":
                    break;
            }
        }else
        {
            echo PREVIEW_NOT_PREVIEW . "..";
        }

    }else
    {
        echo PREVIEW_NOT_PREVIEW . "...";
    }