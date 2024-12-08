const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');


router.get('/status', eventController.getEventsByStatus);
router.put('/updateStatus', eventController.updateEventStatus);
router.get('/schools', eventController.getSchools);

router.get('/statistics', eventController.getEventStatistics);



module.exports = router;
