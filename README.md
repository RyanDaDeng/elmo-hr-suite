1. Install:

git clone https://github.com/RyanDaDeng/elmo-hr-suite.git

2. install laravel and project
	2.1 use .env.example to create a .env file
	2.2 set up db connection in .env
	2.3 run composer install --vvv
	2.4 set up a local server to connect the project, link to /project/public folder
	2.5 access the local server, you may be required to set up a key when you access the server

3. download ngrok - an online distributed server to connect with your local server
	https://ngrok.com/
	3.1 run ./ngrok http -host-header=rewrite {your local server name}:{your local server port}
	3.2 copy Forwarding https link from terminal

4. Go to Slack App Directory: https://api.slack.com/apps

5. Watch Youtube video for the rest steps: https://youtu.be/UMYXxCWdMeE (chang it to 720p)