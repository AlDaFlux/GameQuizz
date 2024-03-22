# game-quizz-bundle

## Install
```
composer require aldaflux/game-quizz-bundle
```

## Config
```
aldaflux_game_quizz:
    fields:
        video:
            youtube: true
            mpg: false
            videolink: true
        audio:
            question: true
            reponse: true
            reponses: true
    folders:
        public: '%kernel.project_dir%/public'
        audio: 'sons'
        video: 'game_quizz_data/videos'
    google_json: '/path/to/google_id.json'
```

## Routing
```
quizz_game_bundle:
    resource: '@AldafluxGameQuizzBundle/Controller/AdminController.php'
    prefix: /game/admin
    type: annotation
    
quizz_game_play_bundle:
    resource: '@AldafluxGameQuizzBundle/Controller/GameController.php'
    prefix: /game/play
    type: annotation

```


## Create symlink
```
php bin/console assets:install --symlink
```
