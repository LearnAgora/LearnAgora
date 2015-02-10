set :stages, %w(dev demo stable learnagora)
set :default_stage, "dev"
set :stage_dir, "app/config/capifony"
require 'capistrano/ext/multistage'

set :application, "learnagora"
set :domain, "54.171.56.1"
set :app_path, "app"

set :repository, "git@bitbucket.org:learnagora/learnagora.git"
set :scm, :git

set :user, "ubuntu"
set :shared_files, ["app/config/parameters.yml", "app/bootstrap.php.cache"]
set :shared_children, [app_path + "/logs", "vendor"]
set :writable_dirs, ["app/cache", "app/logs"]
set :webserver_user, "www-data"
set :permission_method, :acl
set :use_set_permissions, true
set :use_composer, true
set :composer_options, "--verbose --optimize-autoloader"
set :dump_assetic_assets, false
set :model_manager, "doctrine"
set :use_sudo, false
set :clear_controllers, true

default_run_options[:pty] = true
ssh_options[:forward_agent] = true

role :web, domain # Your HTTP server, Apache/etc
role :app, domain, :primary => true # This may be the same as your `Web` server

set :keep_releases, 3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL
