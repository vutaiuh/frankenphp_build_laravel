# Git Setup Local Project

## 1. Xem cấu hình Git global

```bash
git config --global --list
```

## 2. Khởi tạo Git

```bash
git init
```

## 3. Cấu hình user cho project hiện tại

```bash
git config user.name "abc"
git config user.email "abc@gmail.com"
```

## 4. Kiểm tra cấu hình local

```bash
git config --local --list
```

## 5. Lưu thông tin đăng nhập GitHub

```bash
git config credential.helper store
```

## 6. Commit lần đầu

```bash
git add .
git commit -m "Initial commit"
```

## 7. Kết nối GitHub

```bash
git remote add origin https://github.com/username/repository.git
```

## 8. Đổi branch sang main

```bash
git branch -M main
```

## 9. Push lần đầu

```bash
git push -u origin main
```

Username:
```text
abc
```

Password:
```text
GitHub Personal Access Token (PAT)
```
