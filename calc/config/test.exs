import Config

# We don't run a server during test. If one is required,
# you can enable the server option below.
config :calc, CalcWeb.Endpoint,
  http: [ip: {127, 0, 0, 1}, port: 4002],
  secret_key_base: "abifK0XJmq4SXh3YcdQgTtAl2XVdBfi8H1k658PxUO0n36XjyL2lSL83sAaayINh",
  server: false

# In test we don't send emails.
config :calc, Calc.Mailer, adapter: Swoosh.Adapters.Test

# Disable swoosh api client as it is only required for production adapters.
config :swoosh, :api_client, false

# Print only warnings and errors during test
config :logger, level: :warning

# Initialize plugs at runtime for faster test compilation
config :phoenix, :plug_init_mode, :runtime

config :phoenix_live_view,
  # Enable helpful, but potentially expensive runtime checks
  enable_expensive_runtime_checks: true
