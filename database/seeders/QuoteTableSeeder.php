<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $month = $this->command->ask('Enter the month for which you want to generate quotes (format: YYYY-MM)');
        $daysInMonth = Carbon::parse($month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::parse("$month-$day");
            DB::table('quotes')->insert([
                'title' => "Motivation - {$date->format('Y-m-d')}",
                'message' => $this->getRandomQuote(),
                'date' => $date->format('Y-m-d'),
            ]);
        }
    }

    protected function getRandomQuote()
    {


        $quotes = [
            "The only bad workout is the one that didn't happen.",
            "Push yourself because no one else is going to do it for you.",
            "Great things never come from comfort zones.",
            "Fitness is not about being better than someone else. It's about being better than you used to be.",
            "The body achieves what the mind believes.",
            "Strength does not come from physical capacity. It comes from an indomitable will.",
            "Don’t wish for a good body, work for it.",
            "Exercise should be regarded as a tribute to the heart.",
            "Do something today that your future self will thank you for.",
            "The only way to finish is to start.",
            "The first wealth is health.",
            "You don’t get the ass you want by sitting on it.",
            "The body achieves what the mind believes.",
            "The best way to predict your future is to create it.",
            "Your body is the church where Nature asks to be reverenced.",
            "What hurts today makes you stronger tomorrow.",
            "Losing weight is hard. Being overweight is hard. Choose your hard.",
            "The groundwork of all happiness is health.",
            "Exercise is king. Nutrition is queen. Put them together and you’ve got a kingdom.",
            "Be stronger than your excuses.",
            "Eat clean, train dirty.",
            "Fitness is like a relationship. You can’t cheat and expect it to work.",
            "Fitness is not about being better than someone else. It's about being better than you used to be.",
            "It’s not about having time. It’s about making time.",
            "Your body can stand almost anything. It’s your mind that you have to convince.",
            "A one-hour workout is 4% of your day. No excuses.",
            "The hardest lift of all is lifting your butt off the couch.",
            "It's going to be a journey. It's not a sprint to get in shape.",
            "Don’t count the days, make the days count.",
            "The difference between your body this week and next week is what you do for the next seven days to achieve your goal.",
            "Push yourself because no one else is going to do it for you.",
            "Great things never come from comfort zones.",
            "We do not stop exercising because we grow old – we grow old because we stop exercising.",
            "Your limits are only in your mind.",
            "Fitness isn’t a seasonal hobby. Fitness is a lifestyle.",
            "Act as if what you do makes a difference. It does.",
            "Motivation is what gets you started. Habit is what keeps you going.",
            "To enjoy the glow of good health, you must exercise.",
            "You can’t enjoy wealth if you’re not in good health.",
            "Nothing will work unless you do.",
            "Forget failure. Forget mistakes. Forget everything, except what you’re going to do now. And do it.",
            "Fall in love with taking care of yourself. Mind, body, and spirit.",
            "Bodies are like gardens: they need to be cultivated.",
            "The only limit to our realization of tomorrow will be our doubts of today.",
            "If it doesn’t challenge you, it won’t change you.",
            "Never give up on a dream just because of the time it will take to accomplish it. The time will pass anyway.",
            "You are only one workout away from a good mood.",
            "It never gets easier, you just get stronger.",
            "Strive for progress, not perfection.",
            "Respect your body. It’s the only one you get.",
            "Sweat is fat crying.",
            "When you feel like quitting think about why you started.",
            "You don’t have to be great to start, but you have to start to be great.",
            "The only bad workout is the one that didn't happen.",
            "The pain you feel today will be the strength you feel tomorrow.",
        ];


        return $quotes[array_rand($quotes)];
    }
}
