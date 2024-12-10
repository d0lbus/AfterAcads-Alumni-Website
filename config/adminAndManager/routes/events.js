const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');
const eventStatisticsController = require('../controllers/eventStatisticsController');
const multer = require('multer');
const path = require('path');

// Multer storage configuration
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, '../../assets/eventimages/'); 
    },
    filename: (req, file, cb) => {
        cb(null, Date.now() + path.extname(file.originalname)); 
    },
});

// Multer upload instance
const upload = multer({
    storage: storage,
    limits: { fileSize: 5 * 1024 * 1024 }, 
});


router.get('/status', eventController.getEventsByStatus);

router.put('/updateStatus', eventController.updateEventStatus);

router.get('/schools', eventController.getSchools);

// Get event details by ID
router.get('/details/:id', eventController.getEventDetailsById);

router.post('/add', upload.single('eventImage'), eventController.addEvent);

router.delete('/delete/:id', eventController.deleteEvent);


// Update event details
router.put('/update', upload.single('eventImage'), eventController.updateEvent);




router.get('/statistics', eventController.getEventStatistics);


// Routes for event statistics
router.get('/total', eventStatisticsController.getTotalEvents);
router.get('/status', eventStatisticsController.getEventsGroupedByStatus);
router.get('/schools', eventStatisticsController.getEventsGroupedBySchool);
router.get('/monthly', eventStatisticsController.getEventsPerMonth);

module.exports = router;
