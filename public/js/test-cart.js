/**
 * ملف اختبار لسلة التسوق - قم بتضمينه مؤقتًا في الصفحة للاختبار
 */

console.log('تم تحميل ملف اختبار سلة التسوق');

// وظيفة لاختبار الإضافة إلى سلة التسوق
function testAddToCart(productSlug) {
    console.log('جاري اختبار إضافة المنتج إلى سلة التسوق:', productSlug);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);
    
    // محاولة مع JSON
    fetch('/cart/add/' + productSlug, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            quantity: 1
        })
    })
    .then(response => {
        console.log('استجابة الخادم:', response);
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.log('استجابة نصية:', text);
                throw new Error('فشل في تحليل استجابة JSON: ' + e.message);
            }
        });
    })
    .then(data => {
        console.log('بيانات الاستجابة:', data);
        if (data.success) {
            console.log('تمت إضافة المنتج بنجاح! رقم سلة التسوق:', data.cartCount);
        } else {
            console.error('فشل في إضافة المنتج:', data.message);
        }
    })
    .catch(error => {
        console.error('خطأ في إضافة المنتج إلى سلة التسوق:', error);
    });
}

// أضف هذا الكود إلى الكونسول للاختبار:
// 1. افتح صفحة تفاصيل المنتج 
// 2. افتح وحدة تحكم المتصفح (F12)
// 3. انسخ السطر التالي واضغط Enter:
// testAddToCart('product-slug-here'); 