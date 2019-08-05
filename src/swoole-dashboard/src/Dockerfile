# @description php image base on the debian 9.x
#
#                       Some Information
# ------------------------------------------------------------------------------------
# @link https://hub.docker.com/_/debian/      alpine image
# @link https://hub.docker.com/_/php/         php image
# @link https://github.com/docker-library/php php dockerfiles
# @see https://github.com/docker-library/php/tree/master/7.2/stretch/cli/Dockerfile
# ------------------------------------------------------------------------------------
# @build-example docker build . -f Dockerfile -t swoft/swoft
#
FROM swoft/swoft

LABEL maintainer="sakuraovq <sakuraovq@gmail.com>" version="2.0"


# 这里的地址，以客户端中显示的为准
RUN ./deploy_env.sh www.swoole-cloud.com \
    && chmod 755 entrypoint.sh \
    && cp swoole_plus72.so /opt/swoole_plus.so \
    && cp swoole-plus.ini /etc/php7/conf.d/swoole-plus.ini \
    && php -m

