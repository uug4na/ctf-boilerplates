FROM elixir:latest
RUN rm /bin/sh && ln -s /bin/bash /bin/sh
RUN apt-get update && apt-get install -y inotify-tools
WORKDIR /app
RUN mix local.hex --force && mix local.rebar --force
RUN mix archive.install hex phx_new --force
COPY mix.* ./
RUN mix deps.get
ENV MIX_ENV prod
COPY . .
RUN mix compile
RUN chmod -R 555 /app && chmod 444 /app/flag
EXPOSE 4000
CMD ["mix", "phx.server"]