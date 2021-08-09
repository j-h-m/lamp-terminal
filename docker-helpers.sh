# remove one image
docker image rm (docker image ls -aq | head -n 1) -f
# remove one volume
docker volume rm (docker volume ls -q | head -n 1) -f
# remove one container
# docker rm (docker ps -aq | head -n 1) -f

# network info (need correct network id)
docker network inspect -f \
'{{json .Containers}}' fc294d30be76 | \
jq '.[] | .Name + ":" + .IPv4Address'