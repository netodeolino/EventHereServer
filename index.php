
<?php

    include_once 'actions_user.php';
    include_once 'actions_event.php';
  
    if (isset($_POST['action'])) {
        post($_POST['action']);
    } elseif (isset($_GET['action'])) {
        get($_GET['action']);
    } else {
        exit(0);
    }

    function post($action) {
        $res = 0;
        switch ($action) {
            case "add_user": $res = add_user($_POST['user'], $_POST['img']); break;
            case "update_user": $res = update_user($_POST['user'], $_POST['img']); break;
            case "remove_user": $res = remove_user($_POST['id']); break;
            case "add_friend": $res = add_friend($_POST['user_id'], $_POST['friend_id']); break;
            case "remove_friend": $res = remove_friend($_POST['user_id'], $_POST['friend_id']); break;
            case "add_event": $res = add_event($_POST['event']); break;
            case "update_event": $res = update_event($_POST['event']); break;
            case "remove_event": $res = remove_event($_POST['id']); break;
            case "confirm_attendance":
            case "cancel_attendance": $res = update_user_event($_POST['event_id'], $_POST['user_id'], $_POST['confirmed']); break;
            case "invite": $res = invite($_POST['event_id'], $_POST['user_id'], $_POST['confirmed']); break;
            case "invite_many_people": $res = invite_many_people($_POST['event_id'],$_POST['users_ids']); break;
            case "retrieve_password": $res = retrieve_password($_POST['mail']);
        }
        exit($res);
    }

    function get($action) {
        $res = 0;
        switch ($action) {
            case "find_user_by_id": $res = find_user_by_id($_GET['id']); break;
            case "find_user_by_mail": $res = find_user_by_mail($_GET['mail']); break;
            case "find_all_users": $res = find_all_users(); break;
            case "find_block_users": $res = find_block_users($_GET['position'], $_GET['length']); break;
            case "find_friends": $res = find_friends($_GET['user_id']); break;
            case "find_block_friends": $res = find_block_friends($_GET['user_id'],$_GET['position'], $_GET['length']); break;
            case "find_event_by_id": $res = find_event_by_id($_GET['id']); break;
            case "find_block_events": $res = find_block_events($_GET['position'], $_GET['length']); break;
            case "find_all_events": $res = find_all_events(); break;
            case "find_users_confirmed":
            case "find_users_invited": $res = find_users_event($_GET['event_id'], $_GET['confirmed']); break;
            case "find_block_users_confirmed":
            case "find_block_invited": $res = find_block_users_event($_GET['event_id'], $_GET['confirmed'], $_GET['position'], $_GET['length']); break;
            case "find_all_events_by_user": $res = find_all_events_by_user($_GET['id']); break;
            case "find_events_confirmed_by_user": $res = find_events_confirmed_by_user($_GET['id']); break;
            case "find_events_invited_by_user": $res = find_events_invited_by_user($_GET['id']); break;
            case "find_events_historic_by_user": $res = find_events_historic_by_user($_GET['id']); break;
            case "find_block_events_by_user": $res = find_block_events_by_user($_GET['id'], $_GET['position'], $_GET['length']); break;
            case "find_block_events_confirmed_by_user": $res = find_block_events_confirmed_by_user($_GET['id'], $_GET['position'], $_GET['length']); break;
            case "find_block_events_invited_by_user": $res = find_block_events_invited_by_user($_GET['id'], $_GET['position'], $_GET['length']); break;
            case "find_block_events_historic_by_user": $res = find_block_historic_by_user($_GET['id'], $_GET['position'], $_GET['length']); break;
            case "search_available_to_user": $res = search_available_to_user($_GET['user_id'], $_GET['distance'],$_GET['type'],$_GET['specification_type'],$_GET['date'],$_GET['hour'],$_GET['latitude'],$_GET['longitude']); break;
            case "search_block_available_to_user": $res = search_block_available_to_user($_GET['position'], $_GET['length'], $_GET['user_id'], $_GET['distance'],$_GET['type'],$_GET['specification_type'],$_GET['date'],$_GET['hour'],$_GET['latitude'],$_GET['longitude']); break;
            case "find_by_mail_or_name": $res = find_by_mail_or_name($_GET['str']); break;
            case "find_block_by_mail_or_name": $res = find_block_by_mail_or_name($_GET['str'],$_GET['position'],$_GET['length']); break;
            case "find_by_login": $res = find_by_login($_GET['mail'], $_GET['password']); break;
        }
        exit($res);
    }
?>

