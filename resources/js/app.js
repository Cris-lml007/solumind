import '../css/app.css'; // <--- AÑADE ESTA LÍNEA
import './bootstrap';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';
import html2pdf from 'html2pdf.js';
window.Swal = Swal;
window.Chart = Chart;
window.html2pdf = html2pdf;
