@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../elfet/pure/pure
php "%BIN_TARGET%" %*
