<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function ($room, $id) {
    return (int) $user->id === (int) $id;
});

// //to know who is enter and out
/*Broadcast::channel('App.Room.{id}', function ($room, $id) {
  //  return (int) $room->id === (int) $id;
  return $room;
});*/

Broadcast::channel('AppRoom.{userId}', function () {
   
  return true;

});