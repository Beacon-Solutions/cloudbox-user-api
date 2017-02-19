. demo-openrc

openstack server create --flavor m1.tiny --image cirros --nic net-id=PROVIDER_NET_ID --security-group default --key-name mykey $1

