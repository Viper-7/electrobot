IRC bot for ##electronics on libera

Provided Dockerfile is built and published to https://hub.docker.com/repository/docker/viper796/electrobot

Can be run on any server via the commands in startContainter.sh - just add nickserv credentials.


Supported Environment Variables:

| Variable		| Default		| Notes |
|-----------------------|-----------------------|-------|
| BOT_SERVER		| irc.libera.chat	||
| BOT_PORT		| 6667			||
| BOT_NICK		| electrobot		||
| BOT_CHANNEL		| ##electronics		||
| BOT_DEBUG		| 1			| Adds extra console logging |
| BOT_NICKSERV_USER	| -			| Required for +r channels |
| BOT_NICKSERV_PASS	| -			| Required for +r channels |	

