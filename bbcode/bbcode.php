<?php
/*
 * BBCode.php - bbcode.php
 * Copyright (c) 2010-2014 Tony "untitled" Peng.  All rights reserved.
 * <http://www.tonypeng.com/>
 *
 * This file is part of the BBCode project <http://git.io/UVM12g>
 * and is licensed under the MIT license <http://git.io/oT_TKg>.
 */

class BBCode
{
    private $handlers = array();

    public function addTagHandler($tag, $handler)
    {
        if(isset($parsers[$tag]))
            return false;

        $this->handlers[$tag]['handler'] = $handler;
    }

    public function parse($text, $convertNewLines=true)
    {
        $text = htmlentities($text); // prevent html injection

        if($convertNewLines) $text = nl2br($text);

        return $this->parseInternal($text);
    }

    public function treeToText($tree)
    {
        if(!is_array($tree))
            return "";

        //This is it!
        $output = "";

        foreach($tree AS $part)
        {
            if(!is_array($part))
                return "";

            $output_ = $this->generateText($part);

            if(is_array($output_) && isset($output_[2]))
                $output .= $output_[2];
            else
                $output .= $output_;
        }

        return $output;
    }

    private function parseInternal($text, $isrecursive = false)
    {
        //Syntax tree--initialize it to an empty array
        $tree = array();

        // What offset are we at?
        $offset = 0;

        $error = 0;

        $textstart = 0;

        $prevbegin = 0;
        $prevend = -1;

        //Loop...
        while(1)
        {
            //Initialize our found variable to false
            $found = false;

            //Search for a tag!
            $begin = stripos($text, "[", $offset);
            $end = stripos($text, "]", $begin);

            //Didn't find anything?
            if($begin === false || $end === false)
            {
                //Since there's no bbcode, exit the loop.
                break;
            }

            //In that case, let's set our offset as the closing bracket
            $offset = $end;

            //What tag are we dealing with?
            $bbtag = substr($text, $begin+1, $end-$begin-1);

            foreach($this->handlers as $key => $value)
            {
                //Does this tag even exist?
                if(strcmp($bbtag, $key) == 0)
                {
                    //Ok, good, I was starting to get worried. :P
                    $found = true;
                }
            }

            //Now look for the end tag.

            //Was it a valid tag?
            if(!$found)
            {
                //It wasn't a tag?
                continue;
            }
            else
            {
                //Open and end brackets for the closing tag
                $begin_ = stripos($text, "[/".$bbtag, $offset);
                $end_ = stripos($text, "]", $begin_);

                //Not found?
                if(!$begin_ || !$end_)
                {
                    //We have an error!
                    $error++;

                    continue;
                }

                $length = $begin-$prevend-1;
                $textBefore = substr($text, $textstart, $length);

                //Push any text before the bbcode onto the array...
                if(!empty($textBefore))
                    array_push($tree, array("", $textBefore, array()));

                $textstart = $end_+1;

                $offset = $end_;

                $endbbtag = substr($text, $begin_+1, $end_-$begin_-1);//We want to skip the [

                //Are they the same?
                if($endbbtag == "/" . $bbtag)
                {
                    //Yes!  Now we can process the text inside!

                    $intags = substr($text, $end+1, $begin_-$end-1);
                    $original = $intags;

                    $multiple = false;

                    //Children trees...
                    $children = array();

                    //Recursive--find any tags with in children, and find any tags within those, and within those, and within...
                    $children = $this->parseInternal($intags, true);

                    //Push the whole thing on...
                    array_push($tree, array($bbtag, $intags, $children));

                }
                else
                { }

                $prevbegin = $begin;
                $prevend = $end_;
            }
        }

        //Anymore text?
        $lasttext = substr($text, $textstart);

        if($isrecursive)
        {}
        else
        {
            if(!empty($lasttext))
            {
                array_push($tree, array("", $lasttext, array()));
            }
        }

        // return the processed output
        return $tree;
    }

    private function generateText($textarray)
    {
        //NOTE: This assumes that the tag exists!

        if(!is_array($textarray))
            return "";

        $output = array();

        $tag = $textarray[0];
        $wrapped = $textarray[1];
        $children = $textarray[2];

        //It's plain text
        if($tag == "")
        {
            return $wrapped;
        }

        $recursive = array();

        if(count($children) > 0)
        {
            //This needs to be fixed...
            foreach($children AS $child)
            {
                $recursive_ = $this->generateText($child);

                array_push($recursive, $recursive_);
            }
        }

        foreach($recursive AS $rec)
        {
            if(!empty($rec[0]))
            {
                $toreplace = "[".$rec[0]."]".$rec[1]."[/".$rec[0]."]";

                $wrapped = str_ireplace($toreplace, $rec[2], $wrapped);
            }
        }

        $handler = $this->handlers[$tag]["handler"];

        $output[0] = $tag;
        $output[1] = $wrapped;
        $output[2] = call_user_func($handler, $wrapped, array(), array());

        return $output;
    }
}