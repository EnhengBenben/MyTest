# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'application'
set :repo_url, 'git@gitlab.conglinnet.com:bdkq/application.git'

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, '/var/www/my_app_name'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
set :pty, true

# Default value for :linked_files is []
# set :linked_files, fetch(:linked_files, []).push('config/database.yml', 'config/secrets.yml')

# Default value for linked_dirs is []
# set :linked_dirs, fetch(:linked_dirs, []).push('log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'vendor/bundle', 'public/system')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 3

set :ssh_options, { :forward_agent => true }

namespace :deploy do
    desc "Build, composer install"
    after :updated, :build do
        on roles(:web) do
            within release_path do
                execute :composer, "install --no-dev --quiet"
            end
        end
    end

    desc "Copy environment config file"
    after :build, :copy_env do
        on roles(:web) do
            within release_path do
                execute :cp, "#{shared_path}/.env #{release_path}/.env"
            end
        end
    end

    desc "link storage directory to shared folder"
    after :copy_env, :link_logs do
        on roles(:web) do
            within release_path do
                execute :rm, "-Rf #{release_path}/storage"
                execute :ln, "-nfs #{shared_path}/storage #{release_path}/storage"
            end
        end
    end

    # 部署后，新文件的默认ownership是carl:deploy，虽然文件夹河文件都允许组用户进行修改，
    # nginx用户也在deploy组中，但测试是发现总会有权限问题。例如访问网页时，无法在logs文件
    # 夹下面创建log文件（但以nginx用户运行artisan tinker，手动打印log却能够正常创建log文件）。
    # 现在不清楚什么地方造成的问题，因此简单的直接将部署后的文件权限修改为nginx:deploy
    after :link_logs, :permissions do
        on roles(:web) do
            within release_path do
                execute :sudo, :chown, "-R nginx:deploy *"
                execute :sudo, :chown, "-R nginx:deploy .*"
            end
        end
    end

    # Laravel创建的cache file的权限不允许同组用户修改
    # 部署时发现老版本删除有问题，因此添加下面的脚本在cleanup老版本前
    # 先修改cache file的权限
    # 参考: http://stackoverflow.com/questions/19546404/capistrano-v3-not-able-to-cleanup-old-releases
    desc 'Set permissions on old releases before cleanup'
    before :cleanup, :cleanup_permissions do
        on release_roles :all do |host|
            releases = capture(:ls, '-x', releases_path).split
            if releases.count >= fetch(:keep_releases)
                info "Cleaning permissions on old releases"
                directories = (releases - releases.last(1))
                if directories.any?
                    directories.each do |release|
                        execute :sudo, :chmod, '-R', '775', releases_path.join(release)
                    end
                else
                    info t(:no_old_releases, host: host.to_s, keep_releases: fetch(:keep_releases))
                end
            end
        end
    end

end
