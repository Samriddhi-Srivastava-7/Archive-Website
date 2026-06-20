# Reverse Proxy / CDN Plan

The Archive Portal is designed to be deployed behind a CDN/reverse proxy layer.

## Selected CDN
Cloudflare CDN / Reverse Proxy

## Purpose
- HTTPS enforcement
- Static asset caching
- Basic DDoS protection
- Faster loading through edge caching
- Reverse proxy layer before PHP hosting

## Local Development
During local development, the application runs on XAMPP:

Browser → localhost → Apache/PHP → MySQL

CDN/reverse proxy is not applied on localhost because localhost is not publicly accessible.

## Production Flow
User → Cloudflare → Hosting Server → PHP Application → MySQL

## Implementation Requirement
Full CDN implementation requires a real domain with DNS control, such as:

archive.rigelfoundation.org

The current local environment and free hosting subdomain are not enough for full CDN reverse proxy setup.

## Future Cloudflare Setup
1. Add custom domain to Cloudflare.
2. Update domain nameservers to Cloudflare nameservers.
3. Add DNS record:
   - Type: CNAME
   - Name: archive
   - Target: hosting domain
   - Proxy: Enabled
4. Enable:
   - Always Use HTTPS
   - Brotli
   - Auto Minify
   - Cache static assets
   - SSL/TLS mode based on hosting SSL support