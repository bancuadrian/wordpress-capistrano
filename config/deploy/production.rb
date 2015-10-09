role :app, %w{admin@n-soft.ro}
set :deploy_to, "/home/admin/web/mierica.ro"
set :branch, 'master'
server 'admin@n-soft.ro', roles: %w{app}, my_property: :my_value

set :ssh_options, {
    keys: %w(~/.ssh/deployment),
    forward_agent: true
}

namespace :deploy do
    desc "copy env"
    after :published, :build do
        on roles(:app) do
            within release_path  do
                upload! '.env-prod' , "#{release_path}/.env"
            end
        end
    end
end