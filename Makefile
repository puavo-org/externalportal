app_name=externalportal
build_dir=$(CURDIR)/build
sign_dir=$(build_dir)/sign
cert_dir=$(HOME)/.nextcloud/certificates
docker_container=master_nextcloud_1
shared_dir=$(HOME)/dev/nextcloud/nextcloud-docker-dev/data/shared

all: build

.PHONY: build
build: clean npm-build package

.PHONY: npm-build
npm-build:
	npm ci
	npm run build

.PHONY: package
package:
	mkdir -p $(sign_dir)/$(app_name)
	cp -r \
		appinfo \
		css \
		img \
		js \
		l10n \
		lib \
		templates \
		CHANGELOG.md \
		LICENSE \
		LICENSES \
		README.md \
		REUSE.toml \
		screenshot.png \
		$(sign_dir)/$(app_name)
	tar czf $(build_dir)/$(app_name).tar.gz \
		-C $(sign_dir) $(app_name)

.PHONY: sign
sign: package
	mkdir -p $(shared_dir)/sign
	cp -r $(sign_dir)/$(app_name) $(shared_dir)/sign/
	cp $(cert_dir)/$(app_name).key $(cert_dir)/$(app_name).crt $(shared_dir)/sign/
	chmod 644 $(shared_dir)/sign/$(app_name).key
	chmod -R a+rwX $(shared_dir)/sign/$(app_name)
	docker exec -u www-data $(docker_container) php occ integrity:sign-app \
		--privateKey=/shared/sign/$(app_name).key \
		--certificate=/shared/sign/$(app_name).crt \
		--path=/shared/sign/$(app_name)
	cp -r $(shared_dir)/sign/$(app_name) $(sign_dir)/
	rm -rf $(shared_dir)/sign
	tar czf $(build_dir)/$(app_name).tar.gz \
		-C $(sign_dir) $(app_name)

.PHONY: clean
clean:
	rm -rf $(build_dir)
