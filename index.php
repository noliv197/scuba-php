<?php
include 'boot.php';

session_start();

if(auth_user()){
    auth_routes();
} else {
    guest_routes();
}