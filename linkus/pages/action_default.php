<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT * FROM ".dba::get('linkus')." ORDER BY banner DESC");
    if(_rows($qry))
    {
        $cnt = 1; $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $banner = show(_linkus_bannerlink, array("id" => $get['id'], "banner" => string::decode($get['text'])));

            $edit = ''; $delete = '';
            if(permission("links"))
            {
                $edit = show("page/button_edit", array("id" => $get['id'], "action" => "index=linkus&amp;action=admin&amp;do=edit", "title" => _button_title_edit));
                $delete = show("page/button_delete", array("id" => $get['id'], "action" => "index=linkus&amp;action=admin&amp;do=delete", "title" => _button_title_del));
            }

            $show .= show($dir."/linkus_show", array("class" => $class,
                                                     "beschreibung" => string::decode($get['beschreibung']),
                                                     "cnt" => $cnt,
                                                     "banner" => $banner,
                                                     "besch" => string::decode($get['beschreibung']),
                                                     "url" => $get['url']));
            $cnt++;
        }
    }
    else
        $show = _no_entrys_yet;

    $index = show($dir."/linkus", array("head" => _linkus_head, "show" => $show));
}