# DSMS - Render.com Deployment Guide

## ✅ Your Project is Ready for Render!

Your Laravel + PostgreSQL (Supabase) project has been configured for Render deployment.

---

## 📋 Step-by-Step Deployment Instructions

### Step 1: Go to Render Dashboard
1. Open https://render.com
2. Sign up or log in (use GitHub login for easiest setup)
3. Click on **"+ New"** and select **"Web Service"**

### Step 2: Connect GitHub Repository
1. Select **"Public Git repository"**
2. Paste your repo URL: `https://github.com/jonathan1hivetechsol-bot/DSMS-Management-`
3. Click **"Connect"**

### Step 3: Configure Web Service

**Basic Settings:**
- **Name:** `dsms-management`
- **Environment:** `Docker` (automatically selected)
- **Region:** Choose closest to your location

**Build Settings:**
- **Branch:** `main`
- Click **"Advanced"** → **"Auto-deploy"** = ON (optional)

### Step 4: Add Environment Variables ⚠️ IMPORTANT

You **MUST** add these variables. Click **"Add Environment Variable"** for each:

```
APP_NAME=School Management System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:e822pfCnLAKqtNWK7hpin1byd6diVT+xf7AeDL/MXm8=
APP_URL=https://your-render-url.onrender.com

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-south-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=YOUR_SUPABASE_PASSWORD
DB_SSLMODE=require
```

**CRITICAL:** Replace `YOUR_SUPABASE_PASSWORD` with your actual Supabase password!

### Step 5: Deploy

1. Click **"Create Web Service"**
2. Wait 5-10 minutes for build to complete
3. Check logs for any errors
4. Your app will be live at: `https://dsms-management.onrender.com` (or similar)

---

## 🔑 Getting Your Supabase Password

Your connection string is:
```
postgresql://postgres.xehxeystmtrqdpejyvat:[YOUR-PASSWORD]@aws-1-ap-south-1.pooler.supabase.com:6543/postgres
```

- **Username:** `postgres`
- **Password:** Check your Supabase dashboard (Project Settings → Database)

---

## 🚀 After First Deployment

1. **Run Migrations:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   (You can run this via Render's shell access)

2. **Clear Cache:**
   ```bash
   php artisan config:cache
   php artisan cache:clear
   ```

3. **Test Your App:** Visit your Render URL and log in with test credentials

---

## 📝 Login Credentials (After Seeding)

**Admin Portal:**
- URL: `/portal/admin/login`
- Username: Any admin user from database
- Password: `password` (default from seeder)

**Teacher Portal:**
- URL: `/portal/teacher/login`

**Student Portal:**
- URL: `/portal/student/login`

---

## ❌ Troubleshooting

### "Build failed" error
- Check **Logs** tab in Render dashboard
- Fix any errors and push to GitHub → auto-redeploy

### Database connection error
- Verify Supabase password is correct
- Check Internet IP is whitelisted in Supabase (if applicable)
- Verify `DB_SSLMODE=require` is set

### App is slow on first load
- Render free tier has limited resources
- First request takes 30-60 seconds (cold start)
- Subsequent requests are normal speed

---

## 💾 Backup Your Database

Supabase automatically backs up your data. Always keep credentials secure!

---

**Questions?** Check Render docs: https://docs.render.com/

