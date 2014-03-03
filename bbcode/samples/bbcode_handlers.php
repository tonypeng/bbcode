<?php
/*
 * BBCode.php - bbcode_handlers.php
 * Copyright (c) 2010-2014 Tony "untitled" Peng.  All rights reserved.
 * <http://www.tonypeng.com/>
 *
 * This file is part of the BBCode project <http://git.io/UVM12g>
 * and is licensed under the MIT license <http://git.io/oT_TKg>.
 */


// define bbcode handlers
function bbcode_parse_bold($text)
{
    return "<span style=\"font-weight:bold;\">".$text."</span>";
}

function bbcode_parse_italics($text)
{
    return "<span style=\"font-style:italic;\">".$text."</span>";
}

function bbcode_parse_underline($text)
{
    return "<span style=\"text-decoration:underline;\">".$text."</span>";
}

function bbcode_parse_strike($text)
{
    return "<span style=\"text-decoration:line-through;\">".$text."</span>";
}