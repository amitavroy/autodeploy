@setup
$repo = 'git@github.com:amitavroy/autodeploy.git';
$release_dir = '/home/fwdev/autodeploy/releases';
$repo_dir = '/home/fwdev/autodeploy';
$release = 'release_' . date('YmdHis');
@endsetup

@servers(['web' => ['fwdev@192.168.7.162']])

@macro('setup-app', ['on' => 'web'])
    get_code
    make_app_ready
    setup_storage_and_env
    final_setup
@endmacro

@macro('code-update', ['on' => 'web'])
    get_code
    setup_composer
    setup_folders
@endmacro

@task('get_code')
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{$release_dir}};
    git clone {{ $repo }} {{$release}};
@endtask

@task('make_app_ready')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist;
    cp .env.example .env
    php artisan key:generate
    sudo chgrp -R www-data bootstrap/cache
    sudo chmod -R ug+rwx bootstrap/cache
@endtask

@task('setup_storage_and_env')
    # Moving the folders
    cd {{ $repo_dir }}
    mv {{ $release_dir }}/{{ $release }}/storage .
    mv {{ $release_dir }}/{{ $release }}/.env .env

    # Symlink for storage
    cd {{ $release_dir }}/{{ $release }}
    ln -nfs {{ $repo_dir }}/storage storage
    ln -nfs {{ $repo_dir }}/.env .env
@endtask

@task('final_setup')
    # Permission to storage
    cd {{ $repo_dir }}
    sudo chgrp -R www-data storage
    sudo chmod -R ug+rwx storage

    ln -nfs {{ $release_dir }}/{{ $release }} current
@endtask

@task('setup_composer')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist;
@endtask

@task('setup_folders')
    cd {{ $release_dir }}/{{ $release }}
    sudo chgrp -R www-data bootstrap/cache
    sudo chmod -R ug+rwx bootstrap/cache
    rm -rf storage
    php artisan cache:clear
    php artisan route:clear
    php artisan route:clear
    php artisan view:clear

    cd {{ $release_dir }}/{{ $release }}
    ln -nfs {{ $repo_dir }}/storage storage
    ln -nfs {{ $repo_dir }}/.env .env
    
    cd {{ $repo_dir }}
    ln -nfs {{ $release_dir }}/{{ $release }} current
@endtask
