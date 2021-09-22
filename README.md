# game-quizz-bundle


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
