# Setup

## One-time setup

Set up a public/private key pair and add the public key to BitBucket:

https://confluence.atlassian.com/display/BITBUCKET/Set+up+SSH+for+Git

Copy the private key generated above (id_rsa) to an _aws folder in the root
project and name it bitbucket.pem. You will need to create this folder if it
does not exist.

Copy the Amazon AWS private key to the same folder and name it la_amazon.pem.

Make sure your vagrant vm is fully provisioned and ssh into it.
If you had to create the _aws folder above, you WILL need to fully reboot
the vagrant vm.

Ssh into the vagrant vm and run the following commands:

$ cp /opt/learnagora/_aws/*.pem ~

$ chmod 400 ~/*.pem

## Pre-deploy

Before you can deploy, execute the following commands:

$ ssh-agent bash

$ ssh-add ~/*.pem

This previous section really should be automated somehow, soon as someone
finds the time.

# Deploying

A very important thing to realize about deploying as it is configured now,
is that we always deploy from the master branch on the learnagora/learnagora
repository. No matter what the state of your working copy.

This we'll likely change once things get serious, but for now keep in mind 
you will deploy the tip of the master branch as it is at the time of deploy.

## Deploy

From within vagrant:

$ cd /opt/learnagora

$ cap TARGET deploy

Where TARGET is either dev, demo or stable (lowercase), for example:

$ cap dev deploy

## Roll back

If, after deploy, things have gone awfully wrong, simply roll back:

$ cd /opt/learnagora

$ cap TARGET deploy:rollback

## Clean up old releases

$ cd /opt/learnagora

$ cap TARGET deploy:cleanup

# Other stuff

## Capifony documentation

http://capifony.org/
