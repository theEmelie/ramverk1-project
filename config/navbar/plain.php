<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "class" => "my-navbar",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Home",
            "url" => "",
            "title" => "Homepage",
        ],
        [
            "text" => "Questions",
            "url" => "questions",
            "title" => "Questions.",
        ],
        [
            "text" => "Tags",
            "url" => "tags",
            "title" => "Tags.",
        ],
        [
            "text" => "User",
            "url" => "user",
            "title" => "User.",
        ],
        [
            "text" => "About",
            "url" => "om",
            "title" => "About this website.",
        ],
    ],
];
