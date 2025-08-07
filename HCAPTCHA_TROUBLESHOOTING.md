# hCaptcha Troubleshooting Guide

## Common Issues and Solutions

### 1. **hCaptcha Widget Not Visible**

#### **Possible Causes:**
- Empty or invalid site key
- Domain not added to hCaptcha dashboard
- JavaScript loading issues
- CSS conflicts
- Network connectivity issues

#### **Debugging Steps:**

1. **Check Environment Variables:**
   ```bash
   # In your .env file
   HCAPTCHA_SITE_KEY=your_site_key_here
   HCAPTCHA_SECRET_KEY=your_secret_key_here
   ```

2. **Verify Site Key:**
   - Visit: `http://your-domain.com/test-hcaptcha`
   - Check if the site key is displayed correctly
   - Ensure it's not empty or showing "NOT SET"

3. **Check Browser Console:**
   - Open Developer Tools (F12)
   - Go to Console tab
   - Look for any JavaScript errors
   - Check for network errors loading hCaptcha script

4. **Verify Domain Setup:**
   - Log into hCaptcha dashboard
   - Add your domain to the allowed domains list
   - Include both `yourdomain.com` and `www.yourdomain.com`

### 2. **Environment-Specific Issues**

#### **Local Development:**
- Use `localhost` or `127.0.0.1` in hCaptcha dashboard
- Test with debug mode enabled

#### **Production:**
- Ensure domain is added to hCaptcha dashboard
- Check if HTTPS is required
- Verify site key is correct for production domain

### 3. **Quick Fixes**

#### **Clear Cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### **Check Configuration:**
```php
// In config/services.php
'hcaptcha' => [
    'site_key' => env('HCAPTCHA_SITE_KEY'),
    'secret_key' => env('HCAPTCHA_SECRET_KEY'),
],
```

#### **Verify Template:**
```html
<div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.site_key') }}"></div>
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
```

### 4. **Testing Steps**

1. **Visit Test Page:** `http://your-domain.com/test-hcaptcha`
2. **Check Debug Info:** Verify site key is set
3. **Browser Console:** Look for errors
4. **Network Tab:** Check if hCaptcha script loads
5. **Widget Appearance:** Should appear within 3-5 seconds

### 5. **Common Solutions**

#### **If Site Key is Empty:**
- Check `.env` file
- Restart web server
- Clear Laravel cache

#### **If Widget Still Not Visible:**
- Try different browser
- Disable ad blockers
- Check firewall settings
- Verify domain in hCaptcha dashboard

#### **If JavaScript Errors:**
- Check for conflicting scripts
- Ensure jQuery loads before hCaptcha
- Verify no CSP blocking hCaptcha

### 6. **Production Checklist**

- [ ] Domain added to hCaptcha dashboard
- [ ] Site key is correct for production domain
- [ ] HTTPS enabled (if required)
- [ ] No ad blockers interfering
- [ ] Network allows hCaptcha connections
- [ ] CSP headers allow hCaptcha scripts

### 7. **Emergency Fallback**

If hCaptcha continues to fail, you can temporarily disable it:

```php
// In AuthController.php, comment out hCaptcha validation
// 'h-captcha-response' => ['required', new HCaptcha],
```

**Remember:** Always re-enable hCaptcha once the issue is resolved for security.
