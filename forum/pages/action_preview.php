<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
  header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
  if($_GET['what'] == 'thread')
  {
    if($_GET['do'] == 'editthread')
    {
      $qry = db("SELECT * FROM ".dba::get('f_threads')."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
      $get = _fetch($qry);

      $get_datum = $get['t_date'];

      if($get['t_reg'] == 0) $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = $get['t_reg'];
      }
      $editedby = show(_edited_by, array("autor" => cleanautor(),
                                         "time" => date("d.m.Y H:i", time())._uhr));
      $tID = $get['id'];
    } else {
      $get_datum = time();

      if(checkme() == 'unlogged') $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = userid();
      }
      $tID = $_GET['kid'];
    }

    $titel = show(_eintrag_titel_forum, array("postid" => "1",
                                                                            "datum" => date("d.m.Y", $get_datum),
                                                                              "zeit" => date("H:i", $get_datum)._uhr,
                                        "url" => '#',
                                        "edit" => "",
                                        "delete" => ""));
    if($guestCheck)
    {
      $qryu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')."
                  WHERE id = '".$pUId."'");
      $getu = _fetch($qryu);

      $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
      $pn = _forum_pn_preview;
      if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
      else {
        $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
        $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
        }

      if(empty($getu['hp'])) $hp = "";
      else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
      if(data($pUId, "signatur")) $sig = _sig.bbcode::parse_html(data($pUId, "signatur"),1);
      else $sig = "";
      $onoff = onlinecheck(userid());
      $userposts = show(_forum_user_posts, array("posts" => userstats($pUId, "forumposts")+1));
    } else {
        $pn = "";
        $icq = "";
        $email = show(_emailicon_forum, array("email" => eMailAddr($_POST['email'])));
        if(empty($_POST['hp'])) $hp = "";
        else $hp = show(_hpicon_forum, array("hp" => links($_POST['hp'])));
      }



    $qryw = db("SELECT s1.kid,s1.topic,s2.kattopic,s2.sid
                FROM ".dba::get('f_threads')." AS s1
                LEFT JOIN ".dba::get('f_skats')." AS s2
                ON s1.kid = s2.id
                WHERE s1.id = '".convert::ToInt($tID)."'");
    $getw = _fetch($qryw);

    $qrykat = db("SELECT name FROM ".dba::get('f_kats')."
                  WHERE id = '".$getw['sid']."'");
    $kat = _fetch($qrykat);

    $wheres = show(_forum_post_where_preview, array("wherepost" => string::decode($_POST['topic']),
                                                    "wherekat" => string::decode($getw['kattopic']),
                                                    "mainkat" => string::decode($kat['name']),
                                                    "tid" => $_GET['id'],
                                                    "kid" => $getw['kid']));

    if(empty($get['vote'])) $vote = "";
      else $vote = '<tr><td>'.fvote($get['vote']).'</td></tr>';

    if(!empty($_POST['question '])) $vote = _forum_vote_preview;
    else $vote = "";

    $index = show($dir."/forum_posts", array("head" => _forum_head,
                                             "where" => $wheres,
                                             "admin" => "",
                                             "class" => 'class="commentsRight"',
                                             "nick" => cleanautor($pUId, '', $_POST['nick'], $_POST['email']),
                                             "threadhead" => string::decode($_POST['topic']),
                                             "titel" => $titel,
                                             "postnr" => "1",
                                             "pn" => $pn,
                                             "icq" => $icq,
                                             "hp" => $hp,
                                             "email" => $email,
                                             "posts" => $userposts,
                                             "text" =>  bbcode::parse_html($_POST['eintrag']).$editedby,
                                             "status" => getrank($pUId),
                                             "avatar" => useravatar($pUId),
                                             "edited" => $get['edited'],
                                             "signatur" => $sig,
                                             "date" => _posted_by.date("d.m.y H:i", time())._uhr,
                                             "zitat" => _forum_zitat_preview,
                                             "onoff" => $onoff,
                                             "ip" => visitorIp().'<br />'._only_for_admins,
                                             "top" => _topicon,
                                             "lpost" => $lpost,
                                             "lp" => "",
                                             "add" => "",
                                             "nav" => nav("","",""),
                                               "vote" => $vote,
                                             "f_abo" => "",
                                             "show" => $show));
    echo utf8_encode('<table class="mainContent" cellspacing="1" style="margin-top:17px">'.$index.'</table>');
    exit;
  } else {
    if($_GET['do'] == 'editpost')
    {
      $qry = db("SELECT * FROM ".dba::get('f_posts')."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
      $get = _fetch($qry);

      $get_datum = $get['date'];

      if($get['reg'] == 0) $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = $get['reg'];
      }
      $editedby = show(_edited_by, array("autor" => cleanautor(),
                                         "time" => date("d.m.Y H:i", time())._uhr));
      $tID = $get['sid'];
      $cnt = "?";
    } else {
      $get_datum = time();

      if(checkme() == 'unlogged') $guestCheck = false;
      else {
        $guestCheck = true;
        $pUId = userid();
      }
      $tID = $_GET['id'];
      $cnt = cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+2;
    }

    $titel = show(_eintrag_titel_forum, array("postid" => $cnt,
                                                                          "datum" => date("d.m.Y",$get_datum),
                                                                             "zeit" => date("H:i",$get_datum)._uhr,
                                        "url" => '#',
                                        "edit" => "",
                                        "delete" => ""));
    if($guestCheck)
    {
      $qryu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')."
                  WHERE id = '".convert::ToInt($pUId)."'");
      $getu = _fetch($qryu);

      $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
      $pn = _forum_pn_preview;
      if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
      else {
       $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
       $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
      }

      if(empty($getu['hp'])) $hp = "";
      else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
      if(data($pUId, "signatur")) $sig = _sig.bbcode::parse_html(data($pUId, "signatur"));
      else $sig = "";
    } else {
      $icq = "";
      $pn = "";
      $email = show(_emailicon_forum, array("email" => eMailAddr($_POST['email'])));
      if(empty($_POST['hp'])) $hp = "";
      else $hp = show(_hpicon_forum, array("hp" => links($_POST['hp'])));
    }

    $index = show($dir."/forum_posts_show", array("nick" => cleanautor($pUId, '', $_POST['nick'], $_POST['email']),
                                                  "postnr" => "#".($i+($page-1)*settings('m_fposts')),
                                                  "p" => ($i+($page-1)*settings('m_fposts')),
                                                  "class" => 'class="commentsRight"',
                                                  "text" => bbcode::parse_html($_POST['eintrag']).$editedby,
                                                  "pn" => $pn,
                                                  "icq" => $icq,
                                                  "hp" => $hp,
                                                  "email" => $email,
                                                  "status" => getrank($pUId),
                                                  "avatar" => useravatar($pUId),
                                                  "ip" => visitorIp().'<br />'._only_for_admins,
                                                  "edited" => "",
                                                  "posts" => $userposts,
                                                  "titel" => $titel,
                                                  "signatur" => $sig,
                                                  "zitat" => _forum_zitat_preview,
                                                  "onoff" => $onoff,
                                                  "p" => ""));

    update_user_status_preview();
    exit(convert::UTF8('<table class="mainContent" cellspacing="1" style="margin-top:17px">'.$index.'</table>'));
  }
}