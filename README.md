# Payment Subscription Task

## Overview
هذا المشروع يقوم بتنفيذ نظام اشتراكات ودفع مع التركيز على **Clean Architecture**، **مبادئ SOLID**، وقابلية التوسع.  
جميع الميزات المطلوبة في التاسك تم تنفيذها بالكامل.

---

## Postman Collection
- جميع الـ APIs موجودة في **Postman Collection** داخل المشروع لتسهيل الاختبار والتكامل.
https://github.com/khalidgomaa/payment-subscription/blob/main/Payment-subscription.postman_collection.json

## Architecture & Structure
- **Repository Pattern:** لفصل منطق البيانات عن منطق الأعمال.  
- **SOLID Principles:** مطبقة في جميع أجزاء المشروع لضمان صيانة سهلة وقابلية للتوسع.  
- **Requests & Validation:** كل التحقق من البيانات يتم في ملفات `Request`.  
- **Resources:** كل Model له Resource خاص لتنظيم الـ API responses.  
- **Business Logic:** كل المنطق موجود في المكان الصحيح (Service classes).  
- **Dependency Injection:** مستخدمة في Service Providers لربط الـ Repositories بطريقة منظمة.

---
## Clean Code

- **Names:** Classes, methods, and variables are clear and descriptive.  
- **Returns:** Functions return consistent and clear values.  
- **Data:** Data is organized in Models, Resources, and Services.  
- **Separation:** Each layer handles its own responsibility.  
- **Consistency:** Code style and formatting are uniform across the project.  
- **Readability:** Easy for any developer to read and understand.


## Middleware
- **WrapRequest Middleware:** يدير عمليات الـ transaction تلقائيًا لضمان سلامة البيانات.  
- **Admin Middleware:** يحمي الـ routes الخاصة بالـ admin.

---

## Enums & Status
- **StatusEnum:** لتوحيد حالة الطلبات في المشروع كله.  
- **Other Enums:** جميع الـ enums موحدة؛ لم تُستخدم أي enums في الـ migrations لضمان قابلية التوسع.

---

## Responses & Exception Handling
- **Response Macro:** لتوحيد شكل جميع الـ API responses.  
- **Exception Handling:** مدمج في `bootstrap/app.php` لتوحيد شكل الـ errors.

---

## Seeders
- **Admin User & Fixed Plans:** تم إنشاؤهم باستخدام Seeders لتسهيل الإعداد والاختبار.

---

## Stripe Integration
- تم تثبيت تطبيق Stripe محليًا للاختبار والاستماع للـ Webhooks.  
- تم إنشاء حساب Stripe للحصول على الـ API Keys.  
- تم اختبار عملية الدفع باستخدام **Sandbox Mode**.

---


