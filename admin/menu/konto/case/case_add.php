<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['kat']))
    $show = error(_clankasse_empty_kat);
else
{
    db("INSERT INTO ".dba::get('c_kats')." SET `kat` = '".string::encode($_POST['kat'])."'");
    $show = info(_clankasse_kat_added, "?index=admin&amp;admin=konto");
}