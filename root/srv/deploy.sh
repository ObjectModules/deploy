appcfg.py --oauth2 rollback www
cd www
appcfg.py --oauth2 update app.yaml scripts.yaml styles.yaml images.yaml
cd ..
appcfg.py --oauth2 update_dispatch www
appcfg.py --oauth2 update_cron www
