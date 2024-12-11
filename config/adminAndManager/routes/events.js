// References:
// 1. ChatGPT - Assisted with structuring and optimization: https://chat.openai.com
// 2. Express.js Documentation - Router and middleware usage: https://expressjs.com/en/guide/routing.html
// 3. Multer Documentation - File upload handling: https://github.com/expressjs/multer
// 4. Node.js Documentation - Path module: https://nodejs.org/api/path.html
// 5. YouTube - Tutorials on implementing REST APIs:
//    - https://www.youtube.com/watch?v=Oe421EPjeBE
//    - https://www.youtube.com/watch?v=pKd0Rpw7O48

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
