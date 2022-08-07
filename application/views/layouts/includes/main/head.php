<?php
use ItForFree\SimpleAsset\SimpleAssetManager;
use application\assets\CustomCSSAsset;


CustomCSSAsset::add();
SimpleAssetManager::printCss();
?>
<!DOCTYPE html>
<html lang="en">
    <body>
      <div id="container">
        <a href="."><img id="logo" src="images/logo.jpg" alt="Widget News" /></a>