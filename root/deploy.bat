@echo off
rem DEPLOY.BAT - Easy deployer for Google App Engine (GAE) Modules on Windows
rem One click to update all things
rem version 0.01 2015-02-09 Hyip - http://tophyips.info/

rem License: 
rem This file deploy.bat can freely used, modified and redistributed, as 
rem long as credit to the author is kept intact. Please send any feedback,
rem issues or improvements to decalage at laposte.net.

rem CHANGELOG:
rem 2015-02-01 v0.01 PL: - first version, for GAE Modules

rem 1) cd to current directory
CD /d "%~dp0" 

rem 2) test if java directory is in symbolic junction 
if not exist "script" mklink /J script "plugins\java\frame\todomvc\labs\architecture-examples\gwt_gaechannel\src\main\webapp\scripts"

rem 3) deploy php module (using kohana frame)
appcfg.py --oauth2 rollback php.yaml
appcfg.py --oauth2 update php.yaml

pause

rem 4) deploy python module (using django frame)
appcfg.py --oauth2 rollback app.yaml
appcfg.py --oauth2 update app.yaml

rem 5) deploy go module (using docker frame)
rappcfg.py --oauth2 rollback go.yaml 
appcfg.py --oauth2 update go.yaml

rem 5) deploy java module (using gwt frame)
pushd "plugins/java/frame/todomvc/labs/architecture-examples"
call mvn clean package
java -cp rpc-manifest-builder/target/classes org.duilio.gwt.tools.GwtManifestBuilder gwt_gaechannel/target/gwtgaechanneltodo-0.0.1-SNAPSHOT/WEB-INF/deploy/scripts/rpcPolicyManifest
cd gwt_gaechannel
call mvn appengine:update -e

pause

rem 5) deploy GAE dispatch, tsk and cron jobs
popd
cd ..
appcfg.py --oauth2 update_dispatch www
appcfg.py --oauth2 update_queues www
appcfg.py --oauth2 update_cron www

pause
