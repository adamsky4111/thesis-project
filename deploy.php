<?php

namespace Deployer;

//require 'recipe/symfony4.php';

use mysql_xdevapi\Exception;

task('pwd', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

// Project name
set('application', 'webinow');

// Project repository
set('repository', 'git@github.com:adamsky4111/webinow.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('bin/php', 'cd {{release_path}} && docker-compose exec -T php php');
set('bin/composer', function() {

    if (commandExist('composer')) {
        $composer = locateBinaryPath('composer');
    }

    if (empty($composer)) {
        run("cd {{release_path}} && docker-compose exec -T php curl -sS https://getcomposer.org/download/1.10.17/composer.phar -o composer.phar");
        $composer = '{{bin/php}} ./composer.phar';
    }

    return $composer;
});

set('bin/console', function () {
    return parse('bin/console');
});

task('deploy:cache:warmup', function () {
    run('{{bin/php}} {{bin/console}} cache:warmup');
});

task('deploy:cache:clear', function () {
    run('{{bin/php}} {{bin/console}} cache:clear --no-warmup');
});

task('deploy:update_code', function () {
    try {
        run('cd {{release_path}} && git init');
        run('cd {{release_path}} && git remote add origin {{repository}}');
    } catch (\Exception $e) {

    }
    try {
        run('cd {{release_path}} && git checkout -b master');
    } catch(\Exception $exception) {
        run('cd {{release_path}} && git checkout master');
    }
    run('cd {{release_path}} && git reset --hard origin/master');
    run('cd {{release_path}} && git pull origin master');
});


set('default_timeout', 500);
//set('shared_files', ['apps/api/.env.local', 'apps/api/.env.test', 'apps/api/.env']);
set('shared_files', []);
//set('shared_dirs', ['apps/api/public/media']);
set('shared_dirs', []);
set('composer_options', 'install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-suggest');
add('writable_dirs', []);

// hosts
// production
host('production')
    ->hostname('debian@51.195.40.226')
>set('deploy_path', '/var/www/html/webinow');

set('release_path', '/var/www/html/webinow');

// Tasks
task('build-node', function () {
    run('cd {{release_path}} && docker-compose exec -T frontend npm install');
    run('cd {{release_path}} && docker-compose exec -T frontend npm build');
});

task('build-docker', function () {
    run('cd {{release_path}} && docker-compose up -d');
    run('cd {{release_path}} && docker-compose restart');
});

task('deploy:migration', function () {
    run('{{bin/php}} {{bin/console}} doctrine:migration:migrate');
});

task('install-dependencies:php', function (){
    run('cd {{release_path}} && {{bin/composer}}');
});

desc('Installing vendors');
task('deploy:vendors', function () {
    run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
});



task('deploy', function() {

});

after('deploy', 'deploy:update_code');
after('deploy:update_code', 'build-docker');
after('build-docker', 'deploy:vendors');
after('deploy:vendors', 'deploy:cache:clear');
after('deploy:cache:clear', 'deploy:cache:warmup');
after('deploy:cache:warmup', 'deploy:migration');

after('deploy:migration', 'build-node');


// Tasks
;

