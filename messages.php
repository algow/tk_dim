<?php

function messages() {
  return [
    function($data, $details='success') {
      return [
        'error'=> false,
        'details'=> $details,
        'data'=> $data
      ];
    },
    function($details='') {
      return [
        'error'=> true,
        'details'=> $details,
        'data'=> []
      ];
    },
    function($details='something went wrong') {
      return [
        'error'=> true,
        'details'=> $details,
        'data'=> []
      ];
    }
  ];
}

