import { cn } from '@/lib/cn';

const tones = {
    neutral: 'bg-gray-100 text-gray-700',
    orange: 'bg-orange-50 text-orange-700',
    green: 'bg-green-50 text-green-700',
    amber: 'bg-amber-50 text-amber-700',
    blue: 'bg-blue-50 text-blue-700',
    red: 'bg-red-50 text-red-700',
};

export default function Badge({ tone = 'neutral', className, children }) {
    return (
        <span
            className={cn(
                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                tones[tone],
                className,
            )}
        >
            {children}
        </span>
    );
}
