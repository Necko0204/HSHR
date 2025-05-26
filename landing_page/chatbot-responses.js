const chatbotResponses = {
    // Greetings
    'hi': [
        "Hey there! 👋 Welcome to Holy Spirit School of Imus. How can I help you today? Feel free to ask me anything about enrollment, fees, or school activities!",
        "Hi! 😊 It's great to have you here. How can I assist you today? Whether you’re looking for enrollment details or just curious about our programs, I’ve got you covered.",
        "Hello! What can I do for you today? If you have any questions, I’d be happy to help."
    ],
    'hello': [
        "Hi! Thanks for dropping by. How can I assist you? If you’re here for enrollment, I can guide you through the process step by step.",
        "Hey there! I'm here to help with anything about HSSI. What’s on your mind? Let’s make this process easy for you.",
        "Hello! 😊 What brings you here today? If you're unsure where to start, just let me know!"
    ],

    // Enrollment Inquiries
    'enroll': [
        "Awesome! 🎓 Let's get started. Which grade level are you enrolling in—Elementary, Junior High, or Senior High? I can also help you with requirements and schedules.",
        "Exciting! We’d love to have you at HSSI. Could you let me know which grade level you're interested in? I can also provide a list of required documents.",
        "You're making a great choice! 📚 What grade are you looking to enroll in? I can guide you through the steps and give details on our programs."
    ],

    // Yes/No Responses
    'yes': [
        "Great! Let’s move forward. What would you like to do next? If you need details about documents or deadlines, just ask!",
        "Awesome! Do you need help with anything else? I’m here to assist you with all your enrollment needs.",
        "Got it! Let me assist you further. Would you like to know about tuition fees or school schedules?"
    ],
    'no': [
        "No worries! If you need help with anything later, feel free to ask. 😊 I'm always here if you have more questions.",
        "Alright! If you change your mind, I’m here to help. Just message me anytime.",
        "That’s okay! Let me know if there’s anything else I can assist with. I'm happy to help whenever you need."
    ],
    'yes no': [
        "I'm sorry, I don't understand. Could you clarify?"
    ],
     // Employment Opportunities
     'employment opportunities': [
        "We have various teaching and non-teaching positions available. Would you like to know more about the application process or the available positions?",
        "Yes, we are hiring! Would you like to know about the requirements or the positions currently open?"
    ],
    'yes employment': [
        "Great! To apply as a teacher, you'll need:\n📌 Resume/CV\n📌 PRC License (if applicable)\n📌 Transcript of Records\n📌 Certificate of Employment (if any)\n📌 2x2 ID pictures\n📌 Certificate of Good Moral Character\n\nWould you like to know how to submit your application?",
        "Awesome! You can apply by:\n📤 Emailing your documents to hr@hssi.edu.ph\n🏢 Visiting our office during business hours\n📩 Filling out the online application form on our website.\n\nNeed help with anything else?"
    ],
    'no employment': [
        "No problem! If you have any questions later, feel free to ask. 😊",
        "Alright! Let me know if you need assistance in the future. Have a great day!"
    ],

    // Default Response
    'default': [
        "Hmm, I’m not sure about that. Want me to connect you with someone who can help? I’ll make sure you get the right information.",
        "I want to make sure you get the best answer! Let me forward your question to the right person. Would that be okay?",
        "Not sure I got that right! Want to talk to one of our admissions counselors? They’d be happy to assist."
    ],

    // Grade Level Specific
    'elementary': [
        "For elementary enrollment, here’s what you’ll need:\n✅ Birth Certificate\n✅ Report Card from previous school\n✅ 2 pcs 2x2 ID photos\n\nWould you like to set up an appointment with our registrar? I can help you with that!",
        "Our elementary program builds a strong foundation in academics and values. Requirements include:\n📌 Birth Certificate\n📌 Report Card\n📌 2x2 ID Photos\n\nWould you like to know about our curriculum as well?"
    ],

    'high school': [
        "For high school enrollment, you'll need:\n📄 Form 137\n📄 Birth Certificate\n📄 Certificate of Good Moral Character\n📄 2 pcs 2x2 ID photos\n\nWould you like to know more about our programs? We offer a variety of subjects and extracurricular activities!",
        "Our high school program is designed to prepare students for the future! 🚀 Want details about our special programs? We have academic and extracurricular opportunities for students."
    ],

    // Fee Structure
    'fees': [
        "Our tuition fees vary by grade level. Do you need a breakdown for a specific grade? I can also share information about payment plans if you're interested.",
        "I’d be happy to share our tuition details! Which grade level are you asking about? We also offer flexible payment options.",
        "HSSI offers flexible payment options. Would you like to hear about the payment plans available? I can help you find the best one for you."
    ],

    // Requirements
    'applicant': [
        "To apply as a teacher, you'll need:\n📌 Resume/CV\n📌 PRC License (if applicable)\n📌 Transcript of Records\n📌 Certificate of Employment (if any)\n📌 2x2 ID pictures\n📌 Certificate of Good Moral Character\n\nNeed more details? I can guide you through each requirement.",
        "Every teacher applicant needs to submit a few documents before applying. Want me to explain them one by one? I can also check if you need anything extra.",
       
    ],
     // Requirements
     'apply': [
        "To apply as a teacher, you'll need:\n📌 Resume/CV\n📌 PRC License (if applicable)\n📌 Transcript of Records\n📌 Certificate of Employment (if any)\n📌 2x2 ID pictures\n📌 Certificate of Good Moral Character\n\nNeed more details? I can guide you through each requirement.",
        "Every teacher applicant needs to submit a few documents before applying. Want me to explain them one by one? I can also check if you need anything extra.",
       
    ],
     // Requirements
     'application': [
        "To apply as a teacher, you'll need:\n📌 Resume/CV\n📌 PRC License (if applicable)\n📌 Transcript of Records\n📌 Certificate of Employment (if any)\n📌 2x2 ID pictures\n📌 Certificate of Good Moral Character\n\nNeed more details? I can guide you through each requirement.",
        "Every teacher applicant needs to submit a few documents before applying. Want me to explain them one by one? I can also check if you need anything extra.",
       
    ],
    'employment': [
        "To apply as a teacher, you'll need:\n📌 Resume/CV\n📌 PRC License (if applicable)\n📌 Transcript of Records\n📌 Certificate of Employment (if any)\n📌 2x2 ID pictures\n📌 Certificate of Good Moral Character\n\nNeed more details? I can guide you through each requirement.",
        "Every teacher applicant needs to submit a few documents before applying. Want me to explain them one by one? I can also check if you need anything extra.",
       
    ],

    // Schedule Related
    'schedule': [
        "Our office is open Monday to Friday, 8:00 AM to 5:00 PM. Would you like to schedule a visit? I can also tell you the best time to come in for enrollment.",
        "The best time to visit our admissions office is between 9 AM to 3 PM on weekdays. Want me to book an appointment for you? I’d be happy to assist!",
        "We’d love to show you around! When would you like to visit? You can drop by anytime within our office hours."
    ],

    // Location
    'location': [
        "HSSI is located at **123 Education Street, Imus City**. Need directions? I can send you a map or give you step-by-step instructions.",
        "You can find us in the heart of Imus City! 🚏 Want me to send a map? I can also give you landmarks to make it easier to find us.",
        "Our campus is easy to find! Would you like step-by-step directions from your location? I can also suggest the best route if you’re coming by car or public transport."
    ],

    // Contact Information
    'contact': [
        "You can reach us at:\n📞 (555) 123-4567\n📧 admissions@hssi.edu.ph\n\nNeed me to have someone from our team contact you? Just let me know how you'd like to be reached.",
        "Our admissions team is happy to help! Call us at (555) 123-4567. Would you like to schedule a callback? I can arrange it for you.",
        "Want to speak with someone directly? I can connect you! Prefer a call or an email? Let me know what works best."
    ],
    'explain documents': [
        "Sure! Here are the required documents one by one:\n\n1️⃣ **Resume/CV** – A summary of your work experience and qualifications.\n2️⃣ **PRC License** – If applicable, proof of your teaching eligibility.\n3️⃣ **Transcript of Records** – Official document showing your educational background.\n4️⃣ **Certificate of Employment** – If you have prior teaching experience.\n5️⃣ **2x2 ID Photos** – Recent photos required for your records.\n6️⃣ **Certificate of Good Moral Character** – A document from your previous employer or school.\n\nWould you like details on how to submit these?"
    ],
    'extra documents': [
        "Depending on your qualifications and position, you may need additional documents such as:\n📌 A demo teaching video\n📌 Lesson plans for evaluation\n📌 Additional certifications (TESOL, LET, etc.)\n\nWould you like me to check if your qualifications match the requirements?"
    ],
    'submit application': [
        "You can submit your application by:\n📤 **Email** – Send your documents to hr@hssi.edu.ph\n🏢 **Walk-in** – Visit our office during business hours.\n📩 **Online Application** – Fill out the form on our website.\n\nWould you like me to assist you in scheduling an appointment?"
    ],

    // Default Response
    'default': [
        "Hmm, I’m not sure about that. Want me to connect you with someone who can help? I’ll make sure you get the right information.",
        "I want to make sure you get the best answer! Let me forward your question to the right person. Would that be okay?",
        "Not sure I got that right! Want to talk to one of our admissions counselors? They’d be happy to assist."
    ],
     // Specific enrollment phrase
    '2024 25': [
        "That’s great! 🎓 We’d love to have you at HSSI! May I know which grade level you’re enrolling in—Elementary, Junior High, or Senior High? I can guide you through the process and requirements."
    ],
    // Complaints and Concerns
    'complaint': [
        "I understand you have a concern. Here's how you can file a complaint:\n1️⃣ Visit our Admin Office\n2️⃣ Fill out the complaint form\n3️⃣ Submit supporting documents\n\nWould you like me to help you schedule an appointment with our Admin Office?",
        "We take all concerns seriously. You can:\n📝 Submit a written complaint\n📧 Email us at complaints@hssi.edu.ph\n👥 Request a meeting with the concerned department\n\nHow would you like to proceed?"
    ],
    'feedback': [
        "We value your feedback! You can:\n✍️ Fill out our online feedback form\n📮 Drop your suggestions in our feedback box\n📧 Email us at feedback@hssi.edu.ph\n\nWhich method would you prefer?",
        "Thank you for wanting to share your feedback! Would you like to:\n1. Submit it online\n2. Schedule a meeting\n3. Send it via email?"
    ],

    // Student Support Services
    'counseling': [
        "Our Guidance Office provides:\n🤝 Personal Counseling\n📚 Academic Advising\n🎯 Career Counseling\n\nWould you like to schedule an appointment with a counselor?",
        "Need someone to talk to? Our guidance counselors are here to help! When would you like to schedule a session?"
    ],
    'medical assistance': [
        "Our clinic services include:\n🏥 First Aid\n💊 Basic Medication\n🩺 Health Consultations\n\nDo you need immediate medical attention?",
        "We have trained medical staff on campus during school hours. Would you like to know more about our health services?"
    ],

    // Lost and Found
    'lost item': [
        "Lost something? Here's what to do:\n1️⃣ Check our Lost & Found office\n2️⃣ Fill out an item report form\n3️⃣ Provide item description\n\nWould you like to report a lost item now?",
        "Our Lost & Found office is at the Admin building. When did you last see your item? I can help you file a report."
    ],
    'found item': [
        "Thank you for your honesty! Please bring found items to:\n📍 Lost & Found Office (Admin Building)\n⏰ Open 7:30 AM - 4:30 PM\n\nWould you like directions to the office?",
        "Found something? You can turn it in at our Lost & Found office. Would you like to know where it's located?"
    ],

    // Technical Support
    'technical problem': [
        "Having technical issues? Our IT support can help with:\n💻 School Account Access\n📱 Learning Management System\n📧 School Email\n\nWhat specific problem are you experiencing?",
        "For technical support, you can:\n1. Visit our IT Office\n2. Email support@hssi.edu.ph\n3. Call our tech hotline\n\nHow can we assist you?"
    ],
    'forgot password': [
        "Need to reset your password? Here's how:\n1️⃣ Visit password.hssi.edu.ph\n2️⃣ Click 'Forgot Password'\n3️⃣ Follow the recovery steps\n\nWould you like me to guide you through the process?",
        "I can help you recover your account! Do you need to reset your:\n1. Student Portal password\n2. Email password\n3. LMS password?"
    ],

    // Financial Assistance
    'scholarship': [
        "We offer various scholarships:\n🎓 Academic Excellence\n🏆 Sports Scholarship\n🎨 Arts & Culture Grant\n\nWould you like to know the requirements?",
        "Looking for financial aid? Let me tell you about our scholarship programs and requirements. Which type interests you?"
    ],
    'financial aid': [
        "Our financial assistance options include:\n💰 Merit-based scholarships\n🤝 Need-based grants\n📚 Study-now-pay-later plans\n\nWould you like more details about any of these?",
        "We have several financial aid programs to help our students. Would you like to know about the application process?"
    ],

    // School Events
    'events': [
        "Here are our upcoming events:\n📅 Parent-Teacher Conferences\n🎭 School Programs\n🏆 Sports Competitions\n\nWhich event would you like to know more about?",
        "Stay updated with our school activities! Would you like to:\n1. See the events calendar\n2. Get event notifications\n3. Register for an event?"
    ],
    'calendar': [
        "Our school calendar highlights:\n📚 Academic Events\n🎪 School Celebrations\n🏫 Important Dates\n\nWhich month would you like to check?",
        "I can show you our academic calendar! Are you looking for:\n1. Class schedules\n2. Event dates\n3. Exam periods?"
    ],
    'good morning': [
        "Good morning! 🌞 Welcome to HSSI! How may I assist you today?",
        "Morning! Hope you're having a great day. What can I help you with?"
    ],
    'good afternoon': [
        "Good afternoon! 🌤️ Welcome to HSSI! What brings you here today?",
        "Hi there! Hope you're having a nice afternoon. How can I assist you?"
    ],
    'good evening': [
        "Good evening! 🌙 Thank you for visiting HSSI's website. How can I help?",
        "Evening! I'm here to help with any questions you might have about HSSI."
    ],

    // Enrollment Related Queries
    'enroll': [
        "Awesome! 🎓 Let's get started. Which grade level are you enrolling in—Elementary, Junior High, or Senior High? I can also help you with requirements and schedules.",
        "Exciting! We'd love to have you at HSSI. Could you let me know which grade level you're interested in? I can also provide a list of required documents.",
        "You're making a great choice! 📚 What grade are you looking to enroll in? I can guide you through the steps and give details on our programs."
    ],
    'how to enroll': [
        "I'll guide you through our enrollment process! 📝 First, which grade level are you interested in?",
        "Enrolling is easy! Let me walk you through it step by step. Which grade level would you like to enroll in?"
    ],
    'enrollment process': [
        "Here's our enrollment process:\n1. Submit requirements\n2. Assessment test (if applicable)\n3. Pay enrollment fee\n4. Complete registration\n\nWhich grade level are you interested in?"
    ],
    'enrollment requirements': [
        "I'll help you with the requirements! 📋 Could you specify which grade level you're inquiring about?",
        "Let me list down what you'll need. Are you looking to enroll in Elementary, Junior High, or Senior High?"
    ],

    // Fee-Related Queries
    'tuition': [
        "I can help you with tuition information! 💰 Which grade level would you like to know about?",
        "Our tuition varies by grade level. Could you specify which level you're interested in?"
    ],
    'how much': [
        "I can provide you with our fee structure! Which grade level are you asking about?",
        "Let me help you with the costs. Are you inquiring about Elementary, Junior High, or Senior High?"
    ],
    'payment methods': [
        "We accept various payment methods:\n💳 Credit/Debit Cards\n🏦 Bank Transfer\n💰 Cash\n\nWould you like more details about any specific payment option?"
    ],
    'payment plans': [
        "We offer flexible payment plans:\n1. Full Payment (with discount)\n2. Semi-Annual\n3. Quarterly\n4. Monthly\n\nWould you like to know more about any of these options?"
    ],

    // Schedule and Timing Queries
    'when can i enroll': [
        "Enrollment for the upcoming school year is ongoing! 📅 Our office is open Monday to Friday, 8:00 AM to 5:00 PM. Would you like to schedule a visit?",
        "You can start the enrollment process right away! When would you like to visit our campus?"
    ],
    'office hours': [
        "Our office is open:\n⏰ Monday to Friday\n🕐 8:00 AM to 5:00 PM\n\nWould you like to schedule a visit?",
        "We're here to assist you Monday through Friday, 8 AM to 5 PM. When would be a good time for you to visit?"
    ],

    // School Program Queries
    'programs offered': [
        "We offer comprehensive programs for:\n📚 Elementary (Grades 1-6)\n📚 Junior High School (Grades 7-10)\n📚 Senior High School (Grades 11-12)\n\nWhich would you like to know more about?"
    ],
    'curriculum': [
        "Our curriculum is designed to provide excellent academic foundation while nurturing values. Which grade level's curriculum would you like to learn more about?",
        "We offer a balanced curriculum focusing on academics, values, and personal development. Which level are you interested in?"
    ],

    // Facilities and Services
    'facilities': [
        "Our campus features:\n🏫 Modern Classrooms\n📚 Library\n🖥️ Computer Labs\n🏀 Sports Facilities\n🎭 Arts Center\n\nWould you like to know more about any specific facility?"
    ],
    'services': [
        "We provide various services including:\n🚌 School Bus Service\n📚 Tutorial Programs\n🎨 After-school Activities\n💻 Online Learning Support\n\nNeed more details about any of these?"
    ],

    // Transportation and Location
    'how to get there': [
        "Our school is located at 123 Education Street, Imus City. Would you like directions from your location? I can help with public transport routes or driving directions.",
        "Let me help you find us! Are you planning to drive or take public transportation?"
    ],
    'school bus': [
        "Yes, we offer school bus services! 🚌 Would you like to know about our routes and fees?",
        "Our school bus service covers various areas in Imus City. Would you like to see the routes and schedule?"
    ],

    // Document-Related Queries
    'what documents do i need': [
        "The basic requirements include:\n📄 Birth Certificate\n📄 Report Card\n📄 Good Moral Certificate\n📄 2x2 Photos\n\nThe specific requirements may vary by grade level. Which level are you inquiring about?"
    ],
    'requirements list applicant': [
        "I'll help you prepare the documents! Which grade level are you enrolling in? The requirements slightly differ for each level.",
        "Let me provide you with a complete checklist. Are you enrolling in Elementary, Junior High, or Senior High?"
    ],

    // Grade Level Specific
    'elementary': [
        "For elementary enrollment, here's what you'll need:\n✅ Birth Certificate\n✅ Report Card from previous school\n✅ 2 pcs 2x2 ID photos\n\nWould you like to set up an appointment with our registrar?",
        "Our elementary program builds a strong foundation in academics and values. Requirements include:\n📌 Birth Certificate\n📌 Report Card\n📌 2x2 ID Photos\n\nWould you like to know about our curriculum as well?"
    ],
    'junior high': [
        "For Junior High School, you'll need:\n📄 Form 137\n📄 Birth Certificate\n📄 Good Moral Certificate\n📄 2x2 Photos\n\nWould you like to know about our special programs?",
        "Our Junior High School program offers excellent academic preparation! Want to learn about our curriculum and activities?"
    ],
    'senior high': [
        "For Senior High School, we require:\n📄 Form 137\n📄 Birth Certificate\n📄 Good Moral Certificate\n📄 2x2 Photos\n\nWe offer various strands - would you like to know more?",
        "Our Senior High School program prepares students for college and career! Which strand are you interested in?"
    ],

    // Follow-up Responses
    'thank you': [
        "You're welcome! 😊 If you need anything else, don't hesitate to ask. Good luck with your enrollment!",
        "It's my pleasure to help! Let me know if you have any other questions. Have a great day! 👋",
        "You're most welcome! Looking forward to having you at HSSI! 🌟"
    ],
    'bye': [
        "Goodbye! Thanks for your interest in HSSI. Hope to see you soon! 👋",
        "Take care! If you think of any other questions, feel free to come back anytime! 😊",
        "Bye for now! Don't hesitate to reach out if you need more information! 🌟"
    ],
    'can you speak tagalog?': [
        "kaya naman be 💅, pero soon full-time tagalog mode naman, under development pa e 😊",
    ],
};

// List of foul words to detect
const foulWords = ["fuck", "shit", "putanginamo", "gago", "bobo", "tanga"];

// Variable to track chat status
let chatDisabled = false;

// Function to get a random response from an array
function getRandomResponse(responses) {
    return responses[Math.floor(Math.random() * responses.length)];
}

// Function to analyze the entire message
function getEnhancedResponse(message) {
    message = message.toLowerCase().trim();

    // Check if chat is disabled
    if (chatDisabled) {
        return "⚠️ Chat has been disabled due to inappropriate language.";
    }

    // Check for foul words in the full message
    if (foulWords.some(word => message.includes(word))) {
        chatDisabled = true;
        return "🚫 Chat has been disabled due to inappropriate language. Please follow respectful communication.";
    }

    // Check if the message contains key phrases (anywhere in the sentence)
    for (const [key, responses] of Object.entries(chatbotResponses)) {
        if (message.includes(key)) {
            return getRandomResponse(responses);
        }
    }

    // If no keyword matches, return a default response
    return getRandomResponse(chatbotResponses.default);
}