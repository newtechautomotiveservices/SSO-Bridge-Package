# Single Sign On Bridge

## Introduction

> This package is required to be able to use the Single Sign On in your applications.

## Installation
1) Go setup your SSH keys for our github organization as detailed in "FILESERVER:\Processes_and_Procedures\Installing Laravel Packages.docx".
2) Next you must add the repository to your composer.json as shown below.
```json
"repositories": {
    "0": {
      "type": "vcs",
      "url": "https://github.com/newtechautomotiveservices/SSO-Bridge-Package.git"
    }
},
```
3) Then add the package to the "require" section of the composer.json as shown below.
```json
"require": {
    ...
    "newtech/ssobridge": "dev-master"
},
```
