This is just a readme.

mv storage ../../
    cp .env.example ../../.env
    ln -nfs {{ $release_dir }}/.env storage
    ln -nfs {{ $release_dir }}/.env .env
    cd ../..
    ln -nfs {{ $release_dir }}/{{ $release }} current
