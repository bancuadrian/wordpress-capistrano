# config valid only for Capistrano 3.1

set :application, 'my_application_name'
set :repo_url, 'git@github.org:bancuadrian/gitrepo.git'

set :scm, :git

set :stages, ["production"]
set :default_stage, "production"
set :use_sudo, false
set :ssh_options, { :forward_agent => true, :keys => '~/.ssh/deployment'}

set :user, "admin"

$local_db_name = ""
$local_db_user = ""
$local_db_pass = ""
$local_db_file = "mysql.import.sql"
$remote_db_name = ""
$remote_db_user = ""
$remote_db_pass = ""

f = File.open(".env", "rb")
f.each_line do |line|
  values = line.split("=")

  if(values[0] == "DB_NAME") then
    $local_db_name = values[1].strip
  end

  if(values[0] == "DB_USER") then
    $local_db_user = values[1].strip
  end

  if(values[0] == "DB_PASS") then
    $local_db_pass = values[1].strip
  end

  if(values[0] == "DB_HOST") then
      $local_db_host = values[1].strip
    end
end
f.close

f = File.open(".env-prod", "rb")
f.each_line do |line|
  values = line.split("=")

  if(values[0] == "DB_NAME") then
    $remote_db_name = values[1].strip
  end

  if(values[0] == "DB_USER") then
    $remote_db_user = values[1].strip
  end

  if(values[0] == "DB_PASS") then
    $remote_db_pass = values[1].strip
  end
end
f.close

desc "Backup mysql database"
task :mysql_backup do
  run_locally do
    system "mysqldump -u #$local_db_user -p#$local_db_pass --host=#$local_db_host #$local_db_name > #$local_db_file"
  end
end

namespace :deploy do
    after :starting, :mysql_backup
    after :finished, :restart

    desc "Build"
    after :updated, :build do
        on roles(:app) do
            within release_path  do
                execute :php, "composer.phar update" # install dependencies
            end
        end
    end
end

namespace :data do
    task :get do
      on roles(:app) do |host|
        within release_path do
            execute :touch, "#{$local_db_file}"
            execute :mysqldump, "-u #$remote_db_user -p#$remote_db_pass #$remote_db_name > #{$local_db_file}"
            download! "#{release_path}/#{$local_db_file}", "#{$local_db_file}"
            download! "#{release_path}/wp-content/uploads/", "wp-content", :recursive => true
        end
      end

      system "mysql -u #$local_db_user -p#$local_db_pass #$local_db_name --host=#$local_db_host < #$local_db_file"
      system "php _custom/setup_after_deploy.php get"
    end

    task :put do
      system "mysqldump -u #$local_db_user -p#$local_db_pass --host=#$local_db_host #$local_db_name > #$local_db_file"

      on roles(:app) do |host|
        within release_path do
            upload! "#$local_db_file" , "#{release_path}/#$local_db_file"
            execute :mysql, "-u #$remote_db_user -p#$remote_db_pass #$remote_db_name < #$local_db_file"
            execute :php, "_custom/setup_after_deploy.php"
        end
      end

      system "mysql -u #$local_db_user -p#$local_db_pass #$local_db_name --host=#$local_db_host < #$local_db_file"
      system "php _custom/setup_after_deploy.php get"
    end
end