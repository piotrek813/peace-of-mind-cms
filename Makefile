dev/tailwind:
	 npx tailwindcss -i ./style.css -o ./public/assets/css/main.css --watch

dev/serve:
	php -S 0.0.0.0:8000

dev:
	make -j2 dev/tailwind dev/serve

